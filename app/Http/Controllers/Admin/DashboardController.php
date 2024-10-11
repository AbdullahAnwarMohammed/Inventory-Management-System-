<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubProduct;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // جلب جميع SubProducts مع مبيعاتها
        $subProducts = SubProduct::with(['invoiceItems' => function ($query) {
            $query->selectRaw('sub_product_id, SUM(quantity) as total_sales') // استخدم quantity أو أي عمود يمثل عدد المبيعات
                ->groupBy('sub_product_id');
        }])->get();

        // تحضير البيانات للمخطط
        $labels = $subProducts->pluck('name'); // اسم SubProduct
        $salesData = $subProducts->map(function ($subProduct) {
            return $subProduct->invoiceItems->sum('total_sales') ?? 0; // جمع المبيعات لكل SubProduct
        });

        return view("admin.index",compact('labels', 'salesData'));
    }
}
