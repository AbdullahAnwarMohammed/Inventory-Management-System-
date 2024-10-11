<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use PDOException;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Products = Product::all();
        return view("admin.products.index",compact('Products'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.products.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:products,name'
        ]);

        try{
            Product::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);
            toastr()->success('تم اضافة النوع بنجاح');

            return redirect()->route('admin.products.index');

        }catch(PDOException $e)
        {
            toastr()->error('حدث خطأ غير متوقع');

            return redirect()->route('admin.products.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $Product = Product::where('id',$id)->first();
        if(!$Product)
        {
            return redirect()->back();
        }
        return view("admin.products.edit",compact('Product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|unique:products,name,'.$id
        ]);
        try{
            Product::where('id',$id)->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);
            toastr()->success('تم تعديل البيانات بنجاح');

            return redirect()->route('admin.products.index');

        }catch(PDOException $e)
        {
            toastr()->error('حدث خطأ غير متوقع');

            return redirect()->route('admin.products.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Product::where('id',$id)->delete();
        toastr()->success('تم الحذف بنجاح');
        return redirect()->route('admin.products.index');

    }
}
