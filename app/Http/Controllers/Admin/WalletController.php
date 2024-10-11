<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use PDOException;

class WalletController extends Controller
{
    public function index($id)
    {
        $Customer = Customer::where('id', $id)->first();
        if (!$Customer) {
            return redirect()->back();
        }

        return view("admin.wallet.index", compact('Customer'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'amount' => 'required|numeric'
        ]);

        try {
            WalletTransaction::create([
                'wallet_id' => $request->wallet_id,
                'type' => 'deposit',
                'amount' => $request->amount,
                'description' => $request->description
            ]);
            // جلب المحفظة الحالية وتحديث الرصيد
            $wallet = Wallet::findOrFail($request->wallet_id);
            $wallet->balance += $request->amount; // إضافة المبلغ إلى الرصيد الحالي
            $wallet->save(); // حفظ التحديث
            toastr()->success('تم شحن الرصيد بنجاح');
            return redirect()->back();
        } catch (PDOException $e) {
            toastr()->error('حدث خطأ غير متوقع' . $e->getMessage());
            return redirect()->back();
        }
    }
}
