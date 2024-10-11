<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\SubProduct;
use App\Models\SubProductQuantity;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use PDOException;

class SubProductQuantityController extends Controller
{
    public function create($id)
    {
        $SubProduct = SubProduct::where('id', $id)->first();
        if (!$SubProduct) {
            return redirect()->back();
        }

        return view("admin.sub-products-quantity.create", compact('SubProduct'));
    }


    public function store(Request $request)
    {
        // تحقق من صحة البيانات الواردة
        $request->validate([
            'sub_product_id' => 'required|exists:sub_products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric',
        ]);

        try {
            // إضافة الكمية إلى جدول SubProductQuantities
            $subProductQuantity = SubProductQuantity::create([
                'sub_product_id' => $request->sub_product_id,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'total' => $request->total, // حساب الإجمالي
            ]);

            // تحديث كمية المنتج في جدول SubProducts
            $subProduct = SubProduct::find($request->sub_product_id);
            $subProduct->quantity += $request->quantity; // إضافة الكمية الجديدة
            $subProduct->save(); // حفظ التغييرات
            toastr()->success('تم اضافة الكمية بنجاح');
            return redirect()->route('admin.sub-products.index');
        } catch (PDOException $e) {
            toastr()->error('حدث خطأ غير متوقع' . $e->getMessage());
            return redirect()->route('admin.sub-products.index');
        }
    }


   
}
