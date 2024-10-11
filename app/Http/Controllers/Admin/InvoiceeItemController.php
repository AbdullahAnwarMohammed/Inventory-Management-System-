<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\SubProduct;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use PDOException;

class InvoiceeItemController extends Controller
{
    public function create($id)
    {
        $InvoiceItem = Invoice::findOrFail($id);
        return view("admin.invoices.invoice-items.create", compact('InvoiceItem'));
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
            
            // استرجاع الفاتورة باستخدام المعرف
            $invoice = Invoice::where('customer_id', $request->customer_id)->firstOrFail();

            // إضافة المبلغ إلى الرصيد الحالي
            $invoice->total += $total;

            // حفظ التحديث
            $invoice->save();



            // إضافة عناصر الفاتورة
            foreach ($request->items as $item) {
                InvoiceItem::create([
                    'invoice_id' => $request->invoice_id,
                    'product_id' => $item['product_id'],
                    'sub_product_id' => $item['sub_product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['quantity'] * $item['price'],
                ]);
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

    public function destory(Request $request, $id)
    {
        // Invoice Item 
        $InvoiceItem = InvoiceItem::where('id', $id)->first();
        $quantity = $InvoiceItem->quantity;
        $sub_product_id = $InvoiceItem->sub_product_id;

        // SubProduct   
        $SubProduct = SubProduct::where('id', $sub_product_id)->first();
        $SubProduct->quantity +=  $quantity;
        $SubProduct->save();

        // Invoice
        $Invoice = Invoice::where('id', $request->invoice_id)->first();


        // Wallet
        $Wallet = Wallet::where('customer_id', $request->customer_id)->first(); // Retrieve the first matching Wallet instance
        $cleanedTotal = str_replace(',', '', $request->total);
        // Step 2: Convert to a float
        $total = (float)$cleanedTotal; // Use (int)$cleanedTotal if you want an integer
        // Perform the subtraction safely
        $Wallet->balance += $total;
        // Save the changes
        $Wallet->save();
        // // Remove wallet_transactions
        $WalletTrans = WalletTransaction::where('wallet_id', $request->customer_id)
            ->whereDate('created_at', $request->created_at)
            ->delete();

        // // Remove Invoice
        if ($Invoice->invoiceItems->count() == 1) {

            $Invoice = Invoice::where('id', $request->invoice_id)->delete();
        } else {
            InvoiceItem::where('id', $id)->delete();
        }

        toastr()->success('تم حذف الفاتورة بنجاح');
        return redirect()->route('admin.sub-products.index');
    }
    
}
