<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\productsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome')->name('home');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::middleware('auth')->group(function () {
    Route::get('products/alerts', [productsController::class, 'alerts'])->name('products.alerts');


    Route::resource('/sales', App\Http\Controllers\salesController::class);

    Route::resource('/purchases', App\Http\Controllers\purchasesController::class);

    Route::resource('/products', App\Http\Controllers\productsController::class);

    Route::resource('/payment_receipts', App\Http\Controllers\payment_receiptsController::class);

    Route::resource('/users', App\Http\Controllers\UserController::class);

    Route::get('/scan', function () {
        return view('scan');
    });

    Route::post('/scan-product', [ProductsController::class, 'scanProduct']);

    Route::get('/products/by-barcode/{barcode}', [ProductsController::class, 'getByBarcode'])->name('products.byBarcode');

});
