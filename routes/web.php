<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangMasukController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Route CRUD Master
Route::resource('categories', CategoryController::class);
Route::resource('suppliers', SupplierController::class);
Route::resource('products', ProductController::class);

// Route Barang Masuk (Restok)
Route::get('/barang-masuk', [BarangMasukController::class, 'index'])->name('barang-masuk.index');
Route::post('/barang-masuk', [BarangMasukController::class, 'store'])->name('barang-masuk.store');

// Route Kasir & Keranjang
Route::get('/kasir', [TransactionController::class, 'index'])->name('kasir.index');
Route::post('/kasir/add', [TransactionController::class, 'addProduct'])->name('kasir.add');
Route::post('/kasir/update', [TransactionController::class, 'updateCart'])->name('kasir.update');
Route::post('/kasir/remove/{id}', [TransactionController::class, 'removeCart'])->name('kasir.remove');
Route::post('/kasir/checkout', [TransactionController::class, 'checkout'])->name('kasir.checkout');
Route::get('/kasir/print/{id}', [TransactionController::class, 'print'])->name('kasir.print');

// Route Pembayaran
Route::get('/pembayaran', [TransactionController::class, 'pembayaran'])->name('pembayaran.index');

// Route Penjualan (Riwayat Transaksi)
Route::get('/penjualan', function () {
    $penjualan = \App\Models\Transaction::latest()->get();
    return view('penjualan.index', compact('penjualan'));
})->name('penjualan.index');
Route::get('/penjualan/{id}', [TransactionController::class, 'reportDetail'])->name('penjualan.show');
Route::get('/penjualan/print/{id}', [TransactionController::class, 'print'])->name('penjualan.print');

// Route Laporan
Route::get('/laporan', [TransactionController::class, 'report'])->name('laporan.index');
Route::get('/laporan/{id}', [TransactionController::class, 'reportDetail'])->name('laporan.detail');

// Route khusus bersihkan keranjang jika tersangkut Rp 0
Route::get('/clear-cart', function () {
    session()->forget('cart');
    return redirect('/kasir')->with('success', 'Keranjang berhasil dikosongkan!');
});