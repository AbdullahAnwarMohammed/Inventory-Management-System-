<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubProduct;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function getFilteredSubProduct(Request $request)
    {
        $year = $request->year;
        $month = $request->month;
        $timeRange = $request->time_range;
        $productId = $request->product_id;
    
        $query = SubProduct::query();
    
        // فلترة بناءً على المنتج
        if ($productId) {
            $query->where('product_id', $productId);
        }
    
        // فلترة بناءً على السنة
        if ($year) {
            $query->whereHas('invoiceItems', function ($q) use ($year) {
                $q->whereYear('created_at', $year);
            });
        }
    
        // فلترة بناءً على الشهر
        if ($month) {
            $query->whereHas('invoiceItems', function ($q) use ($month) {
                $q->whereMonth('created_at', $month);
            });
        }
    
        // فلترة بناءً على نطاق الوقت
        if ($timeRange) {
            $query->whereHas('invoiceItems', function ($q) use ($timeRange) {
                $now = now();
                switch ($timeRange) {
                    case 'today':
                        $q->whereDate('created_at', $now);
                        break;
                    case 'week':
                        $q->whereBetween('created_at', [$now->copy()->subWeek(), $now]);
                        break;
                    case 'four_weeks':
                        $q->whereBetween('created_at', [$now->copy()->subWeeks(4), $now]);
                        break;
                    case 'thirty_days':
                        $q->whereBetween('created_at', [$now->copy()->subDays(31), $now]);
                        break;
                }
            });
        }
    
        // الحصول على المنتجات الفرعية مع بيانات المبيعات الخاصة بها
        $subProducts = $query->with(['invoiceItems' => function ($q) {
            $q->selectRaw('sub_product_id, SUM(quantity) as total_sales')
              ->groupBy('sub_product_id');
        }])->get();
    
        // تجهيز البيانات للمخطط
        $labels = $subProducts->pluck('name'); // الأسماء للمنتجات الفرعية
        $salesData = $subProducts->map(function ($subProduct) {
            return $subProduct->invoiceItems->sum('total_sales') ?? 0; // إجمالي المبيعات لكل منتج فرعي
        });
    
        // إرجاع البيانات كاستجابة JSON
        return response()->json([
            'labels' => $labels,
            'salesData' => $salesData,
        ]);
    }
}
