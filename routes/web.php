<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;

Route::get('/', function () {
    return view('welcome');
});

// Route CRUD Master
Route::resource('categories', CategoryController::class);
Route::resource('suppliers', SupplierController::class);
Route::resource('products', ProductController::class);

// Route Kasir & Keranjang
Route::get('/kasir', [TransactionController::class, 'index'])->name('kasir.index');
Route::post('/kasir/add', [TransactionController::class, 'addProduct'])->name('kasir.add');
Route::post('/kasir/update', [TransactionController::class, 'updateCart'])->name('kasir.cart.update');
Route::post('/kasir/remove/{id}', [TransactionController::class, 'removeCart'])->name('kasir.cart.remove');
Route::post('/kasir/checkout', [TransactionController::class, 'store'])->name('kasir.checkout');
Route::get('/kasir/print/{id}', [TransactionController::class, 'print'])->name('kasir.print');

// Route Laporan
Route::get('/laporan', [TransactionController::class, 'report'])->name('laporan.index');

// Route khusus bersihkan keranjang jika tersangkut Rp 0
Route::get('/clear-cart', function () {
    session()->forget('cart');
    return redirect('/kasir')->with('success', 'Keranjang berhasil dikosongkan!');
});