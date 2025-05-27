<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PaymentReceiptController;

Route::get('/', function () {
    return view('welcome')->name('home');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::middleware('auth')->group(function () {
    Route::get('products/alerts', [productController::class, 'alerts'])->name('products.alerts');

        Route::get('/sales/search-products', [SaleController::class, 'searchProducts'])->name('sales.search-products');

    Route::resource('/sales', SaleController::class);

    Route::resource('/purchases', PurchaseController::class);

    Route::resource('/products', ProductController::class);

    Route::resource('/payment_receipts', PaymentReceiptController::class);

    Route::resource('/users', UserController::class);

    Route::get('/scan', function () {
        return view('scan');
    });

    Route::post('/scan-product', [ProductController::class, 'scanProduct']);

    Route::get('/products/by-barcode/{barcode}', [ProductController::class, 'getByBarcode'])->name('products.byBarcode');

});

// Route::resource('/payment_receipts', App\Http\Controllers\PaymentReceiptController::class);

// Route::resource('/products', App\Http\Controllers\ProductController::class);

// Route::resource('/purchases', App\Http\Controllers\PurchaseController::class);

// Route::resource('/sales', App\Http\Controllers\SaleController::class);
