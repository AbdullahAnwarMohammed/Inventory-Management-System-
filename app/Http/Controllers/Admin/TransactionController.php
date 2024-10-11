<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TransactionController extends Controller
{
    public function index()
    {
        return view("admin.transaction.index");
    }

    public function find(Request $request, $id)
    {
        // في حالة طلب البيانات عبر AJAX (DataTables)
        if ($request->ajax()) {
            $customer = Customer::findOrFail($id);

            // الحصول على الاستعلام الأساسي
            $transactions = WalletTransaction::where('wallet_id', $customer->wallet->id);

            // التحقق من وجود الفلترة حسب نوع المعاملة
            if ($request->has('type') && !empty($request->type)) {
                $transactions->where('type', $request->type);
            }

            // التحقق من وجود الفلترة حسب التاريخ
            if ($request->has('start_date') && !empty($request->start_date)) {
                $transactions->where('created_at', '>=', $request->start_date);
            }

            if ($request->has('end_date') && !empty($request->end_date)) {
                $transactions->where('created_at', '<=', $request->end_date);
            }

            // حساب المبالغ الإجمالية
            $deposits = WalletTransaction::where('wallet_id', $customer->wallet->id)
                ->where('type', 'deposit')
                ->sum('amount');

            $withdrawals = WalletTransaction::where('wallet_id', $customer->wallet->id)
                ->where('type', 'withdrawal')
                ->sum('amount');

            $netAmount =  $deposits - $withdrawals; // حساب المبلغ الصافي

            return DataTables::of($transactions)
                ->addIndexColumn() // لإضافة عمود أرقام الصفوف تلقائيًا
                ->editColumn('created_at', function ($transaction) {
                    return $transaction->created_at->format('Y-m-d H:i'); // تعديل عرض التاريخ
                })
                ->addColumn('net_amount', function () use ($netAmount) {
                    return $netAmount; // إضافة المبلغ الصافي كعمود جديد
                })
                ->addColumn('action', function ($transaction) {
                    if ($transaction->type === 'deposit') {
                        // عرض رابط التعديل في حالة كانت المعاملة إيداع
                        return '<a href="' . route('admin.transaction.edit', $transaction->id) . '" class="btn btn-sm btn-success">تعديل</a>';
                    }
                    return ''; // عدم عرض أي شيء في حالة كانت المعاملة ليست إيداع
                })
                ->rawColumns(['action']) // تأكد من تفعيل الأعمدة القابلة للتعديل

                ->make(true);
        }

        // عرض صفحة المعاملات
        $customer = Customer::findOrFail($id);
        return view('admin.transaction.find', compact('customer'));
    }

    public function edit($id)
    {

        $transaction = WalletTransaction::findOrFail($id);
        return view("admin.transaction.edit", compact('transaction'));
    }

    public function update(Request $request, $id)
    {
    
        // استرجاع عملية الإيداع أو السحب
        $transaction = WalletTransaction::findOrFail($id);
        $wallet = $transaction->wallet;
    
        // الحصول على البيانات الجديدة من الطلب
        $operationType = $request->input('operation_type'); // نوع العملية: إيداع أو سحب
        $operationAmount = $request->input('operation_amount'); // القيمة المضافة أو المسحوبة
    
        // تأكيد صحة البيانات المدخلة
        $request->validate([
            'operation_type' => 'required|in:deposit,withdrawal',
            'operation_amount' => 'required|numeric|min:0.01'
        ]);
        // حساب القيمة الجديدة بناءً على نوع العملية
        if ($operationType === 'deposit') {
            // تعديل العملية لتكون إيداع وتحديث الرصيد
            // $difference = $operationAmount - $transaction->amount; // فرق القيمة الجديد والقديم
            $wallet->balance += $request->operation_amount;
        } elseif ($operationType === 'withdrawal') {
            // تعديل العملية لتكون سحب وتحديث الرصيد
            // $difference = $transaction->amount - $operationAmount; // فرق القيمة القديم والجديد
            $wallet->balance -= $request->operation_amount;
        }
    
        // تحديث المعاملة بالقيمة الجديدة
        $transaction->amount = $request->new_amount;
        // $transaction->type = $operationType;
        
        // حفظ التحديثات في قاعدة البيانات
        $transaction->save();
        $wallet->save();
        toastr()->success('تم التعديل بنجاح');

        return redirect()->back();

        // إعادة التوجيه مع رسالة نجاح
        // return redirect()->route('admin.transaction.find', $wallet->customer_id)->with('success', 'تم تعديل المعاملة بنجاح.');
    }
    
}
