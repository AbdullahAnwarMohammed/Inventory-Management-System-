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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم العميل
            $table->string('email')->nullable()->unique(); // بريد إلكتروني فريد لكل عميل
            $table->string('phone')->nullable(); // رقم الهاتف (اختياري)
            $table->string('address')->nullable(); // العنوان (اختياري)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
