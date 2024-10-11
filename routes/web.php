<?php

use App\Http\Controllers\Admin\AlbunudController;
use App\Http\Controllers\Admin\ChartController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DailyExpenseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\InvoiceeItemController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SubProductController;
use App\Http\Controllers\Admin\SubProductQuantityController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\WalletController;
use App\Models\SubProduct;
use App\Models\Wallet;
use Illuminate\Database\Events\TransactionCommitted;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'admin.'], function () {
    Route::get("/", [DashboardController::class, 'index'])->name("home");
    Route::view("/calculator", 'admin.calculator')->name("calculator");

    // الاصناف
    Route::resource("/products", ProductController::class);
    Route::resource("/sub-products", SubProductController::class);

    

    // الانواع 
    Route::group(['as' => 'subProductsQuantity.','name' => 'subProductsQuantity'], function () {
        Route::get("/sub-products-quantity/{id}", [SubProductQuantityController::class, 'create'])->name("create");
        Route::post("/sub-products-quantity/store", [SubProductQuantityController::class, 'store'])->name("store");
    });

    Route::get('/products/{product}/sub-products', function ($product) {
        $subProducts = SubProduct::where('product_id', $product)->get();
        return response()->json($subProducts);
    });
    
    // العملاء
    Route::resource("customers", CustomerController::class);
    Route::get('/customers/{customer}/wallet-balance', function ($customer) {
        $wallet = Wallet::where('customer_id', $customer)->first();
        return response()->json(['balance' => $wallet ? $wallet->balance : 0]);
    });



    // المحفظة
    Route::group(['prefix' => 'wallet', 'as' => 'wallet.'], function () {
        Route::get("/{id}", [WalletController::class, 'index'])->name("home");
        Route::post("/store", [WalletController::class, 'store'])->name("store");
    });

    // الفاتورة
    Route::group(['prefix' => 'invoices', 'as' => 'invoices.'], function () {
        Route::get("/index", [InvoiceController::class, 'index'])->name("index");
        Route::get("/create", [InvoiceController::class, 'create'])->name("create");
        Route::post("/store", [InvoiceController::class, 'store'])->name("store");
        Route::get("/show/{id}", [InvoiceController::class, 'show'])->name("show");
    });


    // Invoice Items
    Route::group(['prefix' => 'invoice-items', 'as' => 'invoiceItem.'], function () {
        Route::get("/create/{id}", [InvoiceeItemController::class, 'create'])->name("create");
        Route::post("/store", [InvoiceeItemController::class, 'store'])->name("store");
        Route::delete("/invoice-items/destory/{id}", [InvoiceeItemController::class, 'destory'])->name("destory");

    });


    // Expense بند المصاريف
    Route::resource('albunuds', AlbunudController::class);
    

    // Daily Expense المصاريف اليومية
    Route::resource('daily-expense', DailyExpenseController::class);

    // Charts 
    Route::get('/filtered-sub-product-sales', [ChartController::class, 'getFilteredSubProduct'])->name('filteredSubProductSales');

    // عمليات الايداع والحسب
    Route::group(['prefix' => 'transaction', 'as' => 'transaction.'], function () {

    Route::get("/",[TransactionController::class,'index'])->name("home");
    Route::get("/find/{id}",[TransactionController::class,'find'])->name("find");

    Route::get("/edit/{id}",[TransactionController::class,'edit'])->name('edit');
    Route::put("/update/{id}",[TransactionController::class,'update'])->name('update');

    });
});
