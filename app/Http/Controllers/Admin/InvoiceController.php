<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\SubProduct;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDOException;
use Yajra\DataTables\Facades\DataTables;

class InvoiceController extends Controller
{
    // InvoicesController.php
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $invoices = Invoice::with(['customer', 'invoiceItems.subProduct.product'])->orderBy('id','desc');
    
            // إضافة فلترة حسب اسم العميل
            if ($request->has('search') && $request->search != '') {
                $searchTerm = $request->search;
                $invoices->whereHas('customer', function ($query) use ($searchTerm) {
                    $query->where('name', 'like', "%{$searchTerm}%");
                });
            }
    
            // فلترة السنة
            if ($request->year) {
                $invoices->whereYear('created_at', $request->year);
            }
    
            // فلترة الشهر
            if ($request->month) {
                $invoices->whereMonth('created_at', $request->month);
            }
    
            // فلترة الوقت
            if ($request->time_filter) {
                $today = now();
                switch ($request->time_filter) {
                    case 'today':
                        $invoices->whereDate('created_at', $today);
                        break;
                    case 'week':
                        $invoices->where('created_at', '>=', $today->subDays(7));
                        break;
                    case 'four_weeks':
                        $invoices->where('created_at', '>=', $today->subWeeks(4));
                        break;
                    case 'thirty_days':
                        $invoices->where('created_at', '>=', $today->subDays(31));
                        break;
                }
            }
    
            // فلترة حسب المنتج الرئيسي
            if ($request->product_id) {
                $invoices->whereHas('invoiceItems.subProduct.product', function ($query) use ($request) {
                    $query->where('id', $request->product_id);
                });
            }
    
            // فلترة حسب المنتج الفرعي
            if ($request->sub_product_id) {
                $invoices->whereHas('invoiceItems.subProduct', function ($query) use ($request) {
                    $query->where('id', $request->sub_product_id);
                });
            }
            

    
            return DataTables::of($invoices)
                ->addIndexColumn()
                ->addColumn('customer_name', function ($invoice) {
                    return $invoice->customer->name;
                })
                ->addColumn('total', function ($invoice) {
                    return number_format($invoice->total, 2);
                })
                ->addColumn('actions', function ($invoice) {
                    return view('admin.invoices.partials.actions', compact('invoice'))->render();
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('admin.invoices.index');
    }
    



    public function create()
    {
        $customers = Customer::all(); // جلب جميع العملاء
        $products = Product::all(); // جلب جميع المنتجات

        return view("admin.invoices.create", compact('customers', 'products'));
    }

    public function store(Request $request)
    {

        // تحقق من صحة البيانات المدخلة
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.sub_product_id' => 'required|exists:sub_products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        try {
            // احصل على العميل
            $customer = Customer::find($request->customer_id);


            // احسب المجموع الكلي
            $total = 0;
            foreach ($request->items as $item) {
                $total += $item['quantity'] * $item['price'];
            }

            // تحقق مما إذا كان العميل لديه رصيد كافٍ
            // if ($customer->wallet_balance < $total) {
            //     toastr()->error('الرصيد غير كافي');

            //     return redirect()->back();
            // }
            // إنشاء فاتورة جديدة
            $invoice = Invoice::create([
                'customer_id' => $customer->id,
                'total' => $total,
                // يمكنك إضافة المزيد من الحقول حسب الحاجة
            ]);

            // إضافة عناصر الفاتورة
            foreach ($request->items as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'],
                    'sub_product_id' => $item['sub_product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['quantity'] * $item['price'],
                ]);

                // طرح الكمية من المخزون
                $subProduct = SubProduct::find($item['sub_product_id']);
                if ($subProduct) {
                    if ($subProduct->quantity < $item['quantity']) {
                        // تحقق مما إذا كانت الكمية المتاحة كافية
                        toastr()->error("الكمية المطلوبة لـ {$subProduct->name} أكبر من المخزون المتاح.");
                        return redirect()->back();
                    }

                    $subProduct->quantity -= $item['quantity']; // طرح الكمية المشتراة
                    $subProduct->save(); // حفظ التحديثات
                }
            }




            WalletTransaction::create([
                'wallet_id' => $customer->wallet->id,
                'type' => 'withdrawal',
                'amount' => $total,
                'description' => 'شراء بضاعة'
            ]);
            // جلب المحفظة الحالية وتحديث الرصيد
            $wallet = Wallet::findOrFail($customer->id);
            $wallet->balance -= $total; // إضافة المبلغ إلى الرصيد الحالي
            $wallet->save(); // حفظ التحديث
            toastr()->error('تم اضافة الفاتورة بنجاح');
            return redirect()->back();
        } catch (PDOException $e) {
            toastr()->error('حدث خطأ غير متوقع' . $e->getMessage());
            return redirect()->back();
        }
    }
    public function show($id)
    {
        // جلب الفاتورة مع العميل والتفاصيل
        $invoice = Invoice::with('customer', 'invoiceItems')->findOrFail($id);

        return view('admin.invoices.show', compact('invoice'));
    }
}
