<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->foreignId('sub_product_id')->constrained('sub_products')->onDelete('cascade');
            $table->integer('quantity'); // الكمية المطلوبة
            $table->decimal('price', 10, 2); // سعر الوحدة
            $table->decimal('total', 10, 2); // المجموع (الكمية * سعر الوحدة)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
