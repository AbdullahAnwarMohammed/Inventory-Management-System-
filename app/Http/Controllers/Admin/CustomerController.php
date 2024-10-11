<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PDOException;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $customers = Customer::query();

            // منطق الفلترة للعملاء
            if ($request->filterType) {
                if ($request->filterType === 'creditor') {
                    $customers->whereHas('wallet', function ($query) {
                        $query->where('balance', '>', 0);
                    });
                } elseif ($request->filterType === 'debtor') {
                    $customers->whereHas('wallet', function ($query) {
                        $query->where('balance', '<=', 0);
                    });
                }
            }

            // منطق الفلترة للشراء
            if ($request->purchaseFilter) {
                if ($request->purchaseFilter === 'purchased_today') {
                    $customers->whereHas('invoices', function ($query) {
                        $query->whereDate('created_at', Carbon::today());
                    });
                } elseif ($request->purchaseFilter === 'not_purchased_today') {
                    $customers->whereDoesntHave('invoices', function ($query) {
                        $query->whereDate('created_at', Carbon::today());
                    });
                }
            }

            return DataTables::of($customers)
                ->addIndexColumn()
                ->addColumn('wallet_balance', function ($customer) {
                    $balance = $customer->wallet ? $customer->wallet->balance : 0; // تجنب الأخطاء
                    $backgroundColor = $balance <= 0 ? 'text-danger' : 'text-success';
                    return "<span class='fw-bold  {$backgroundColor}'>{$balance}</span>";
                })
                ->addColumn('actions', function ($customer) {
                    return view('admin.customers.partials.actions', compact('customer'))->render();
                })
                ->rawColumns(['wallet_balance', 'actions'])
                ->make(true);
        }

        return view('admin.customers.index');
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.customers.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:customers,name'
        ]);
        try {
            $Customer = Customer::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
            ]);

            Wallet::create([
                'customer_id' => $Customer->id
            ]);
            if($request->modal)
            {
                return response()->json($Customer, 201);

            }else{
                
                toastr()->success('تم اضافة البيانات بنجاح');
                return redirect()->route('admin.customers.index');
    
            }

        } catch (PDOException $e) {
            toastr()->error('حدث خطأ غير متوقع');
            return redirect()->route('admin.customers.index');
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
        $Customer = Customer::where('id', $id)->first();
        if (!$Customer) {
            return redirect()->back();
        }
        return view("admin.customers.edit", compact('Customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|unique:customers,name,' . $id
        ]);
        try {
            $Customer = Customer::where('id', $id)->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
            ]);

            toastr()->success('تم اضافة البيانات بنجاح');
            return redirect()->route('admin.customers.index');
        } catch (PDOException $e) {
            toastr()->error('حدث خطأ غير متوقع');
            return redirect()->route('admin.customers.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Customer::where('id', $id)->delete();
        toastr()->success('تم حذف البيانات بنجاح');
        return redirect()->route('admin.customers.index');
    }
}
