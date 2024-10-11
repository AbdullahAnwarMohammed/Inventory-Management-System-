<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Albunud;
use App\Models\DailyExpense;
use Illuminate\Http\Request;
use PDOException;
use Yajra\DataTables\Facades\DataTables;

class DailyExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = DailyExpense::with('albunud');

            // فلترة حسب ID الفئة
            if ($request->has('albunud_id') && $request->albunud_id != '') {
                $query->where('albunud_id', $request->albunud_id);
            }

            // فلترة حسب السنة
            if ($request->has('year') && $request->year != '') {
                $query->whereYear('created_at', $request->year);
            }

            // فلترة حسب الشهر
            if ($request->has('month') && $request->month != '') {
                $query->whereMonth('created_at', $request->month);
            }



            // فلترة الوقت باستخدام switch
            if ($request->has('time_filter') && $request->time_filter != '') {
                $today = now(); // الحصول على التاريخ الحالي

                switch ($request->time_filter) {
                    case 'today':
                        $query->whereDate('created_at', $today);
                        break;
                    case 'week':
                        $query->where('created_at', '>=', $today->subDays(7));
                        break;
                    case 'four_weeks':
                        $query->where('created_at', '>=', $today->subWeeks(4));
                        break;
                    case 'thirty_days':
                        $query->where('created_at', '>=', $today->subDays(31));
                        break;
                }
            }

            // حساب مجموع المبالغ بعد الفلترة
            $totalAmount = $query->sum('amount');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('category_name', function ($row) {
                    return $row->albunud ? $row->albunud->name : 'N/A';
                })
                ->addColumn('actions', function ($row) {
                    return view('admin.daily-expense.partials.actions', compact('row'))->render();
                })
                ->with('totalAmount', $totalAmount) // إرجاع المجموع مع الاستجابة
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('admin.daily-expense.index');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $Albunds = Albunud::all();

        return view("admin.daily-expense.create", compact('Albunds'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'albunud_id' => 'required',
            'amount' => 'required|numeric'
        ]);
        try {
            DailyExpense::create([
                'albunud_id' => $request->albunud_id,
                'amount' => $request->amount,
                'description' => $request->description
            ]);
            toastr()->success('تم الاضافة بنجاح');
            return redirect()->route('admin.daily-expense.index');
        } catch (PDOException $e) {
            toastr()->error('حدث خطأ غير متوقع' . $e->getMessage());

            return redirect()->route('admin.daily-expense.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Item = DailyExpense::findOrFail($id);
        return view("admin.daily-expense.show",compact('Item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $Item = DailyExpense::findOrFail($id);

        return view("admin.daily-expense.edit", compact('Item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'albunud_id' => 'required',
            'amount' => 'required|numeric'
        ]);

        try {
            DailyExpense::where('id', $id)->update([
                'albunud_id' => $request->albunud_id,
                'amount' => $request->amount,
                'description' => $request->description
            ]);
            toastr()->success('تم تعديل البيانات بنجاح');
            return redirect()->route('admin.daily-expense.index');
        } catch (PDOException $e) {
            toastr()->error('حدث خطأ غير متوقع' . $e->getMessage());

            return redirect()->route('admin.daily-expense.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DailyExpense::where('id', $id)->delete();
        toastr()->success('تم الحذف بنجاح');
        return redirect()->route('admin.daily-expense.index');
    }
}
