<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SubProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDOException;
use Yajra\DataTables\Facades\DataTables;

class SubProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $subProducts = SubProduct::with('quantities', 'product');
    
            // تطبيق الفلترة
            if ($request->filter) {
                if ($request->filter === 'most_sold') {
                    // تصنيف حسب الكمية المباعة الأكثر
                    $subProducts->withCount(['invoiceItems as total_sold' => function ($query) {
                        $query->select(DB::raw("SUM(quantity)"));
                    }])->orderBy('total_sold', 'desc');
                } elseif ($request->filter === 'least_sold') {
                    // تصنيف حسب الكمية المباعة الأقل
                    $subProducts->withCount(['invoiceItems as total_sold' => function ($query) {
                        $query->select(DB::raw("SUM(quantity)"));
                    }])->orderBy('total_sold', 'asc');
                }
            }
    
            return DataTables::of($subProducts)
                ->addIndexColumn()
                ->addColumn('price', function ($subProduct) {
                    return $subProduct->quantities->last() ? $subProduct->quantities->last()->price : 'لم يتم الإضافة';
                })
                ->addColumn('product_name', function ($subProduct) {
                    return $subProduct->product->name;
                })
                ->addColumn('actions', function ($subProduct) {
                    return view('admin.sub-products.partials.actions', compact('subProduct'))->render();
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
    
        $SubProducts = SubProduct::all();
        return view('admin.sub-products.index', compact('SubProducts'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $Products = Product::all();

        return view("admin.sub-products.create", compact('Products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        //    protected $fillable = ['product_id','name','price','quantity','unit'];
        $request->validate([
            'name' => 'required|unique:sub_products,name',
            'product_id' => 'required',
            'unit' => 'required||numeric',
        ]);
        try {
            SubProduct::create([
                'name' => $request->name,
                'product_id' => $request->product_id,
                'unit' => $request->unit,
            ]);

            toastr()->success('تم اضافة النوع بنجاح');
            return redirect()->route('admin.sub-products.index');
        } catch (PDOException $e) {
            toastr()->error('حدث خطأ غير متوقع');
            return redirect()->route('admin.sub-products.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        $SubProduct = SubProduct::findOrFail($id);
        return view("admin.sub-products.show",compact('SubProduct'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $SubProduct = SubProduct::where('id', $id)->first();
        $Products = Product::all();

        if (!$SubProduct) {
            toastr()->error('حدث خطأ غير متوقع');
            return redirect()->back();
        }
        return view("admin.sub-products.edit", compact('SubProduct', 'Products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|unique:sub_products,name,' . $id,
            'product_id' => 'required',
            'unit' => 'required||numeric',
        ]);
        try {
            SubProduct::where('id', $id)->update([
                'name' => $request->name,
                'product_id' => $request->product_id,
                'unit' => $request->unit,
            ]);

            toastr()->success('تم اضافة النوع بنجاح');
            return redirect()->route('admin.sub-products.index');
        } catch (PDOException $e) {
            toastr()->error('حدث خطأ غير متوقع');
            return redirect()->route('admin.sub-products.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        SubProduct::where('id',$id)->delete();
        toastr()->success('تم الحذف بنجاح');
        return redirect()->route('admin.sub-products.index');
    }
}
