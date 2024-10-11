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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained('wallets')->onDelete('cascade'); // ربط مع المحفظة
            $table->enum('type', ['deposit', 'withdrawal']); // نوع العملية (إيداع أو خصم)
            $table->decimal('amount', 10, 2); // قيمة المعاملة
            $table->string('description')->nullable(); // وصف العملية
            $table->timestamps(); // تتبع وقت الإضافة والتعديل
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
