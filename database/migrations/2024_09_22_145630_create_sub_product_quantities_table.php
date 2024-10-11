<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sub_product_quantities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_product_id')->constrained('sub_products')->onDelete('cascade');
            $table->decimal('price', 10, 2); // سعر الوحدة
            $table->integer('quantity'); // الكمية المضافة
            $table->decimal('total', 10, 2); // حساب تلقائي للمجموع (السعر × الكمية)
            $table->timestamp('date')->default(DB::raw('CURRENT_TIMESTAMP')); // تاريخ إضافة الكمية (افتراضيًا اليوم)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_product_quantities');
    }
};
