<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\UserController;

// -------------------------------------------------------------
// ROUTE UNTUK GUEST (Belum Login)
// -------------------------------------------------------------
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// -------------------------------------------------------------
// ROUTE UNTUK USER (Sudah Login)
// -------------------------------------------------------------
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Master Data & Transaksi
    Route::resource('categories', CategoryController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('products', ProductController::class);
    Route::resource('users', UserController::class);

    // Barang Masuk
    Route::get('/barang-masuk', [BarangMasukController::class, 'index'])->name('barang-masuk.index');
    Route::post('/barang-masuk', [BarangMasukController::class, 'store'])->name('barang-masuk.store');

    // Kasir
    Route::get('/kasir', [TransactionController::class, 'index'])->name('kasir.index');
    Route::post('/kasir/add', [TransactionController::class, 'addProduct'])->name('kasir.add');
    Route::post('/kasir/update', [TransactionController::class, 'updateCart'])->name('kasir.update');
    Route::post('/kasir/remove/{id}', [TransactionController::class, 'removeCart'])->name('kasir.remove');
    Route::post('/kasir/checkout', [TransactionController::class, 'checkout'])->name('kasir.checkout');
    Route::get('/kasir/print/{id}', [TransactionController::class, 'print'])->name('kasir.print');

    // Pembayaran & Penjualan
    Route::get('/pembayaran', [TransactionController::class, 'pembayaran'])->name('pembayaran.index');
    Route::get('/penjualan', function () {
        $penjualan = \App\Models\Transaction::latest()->get();
        return view('penjualan.index', compact('penjualan'));
    })->name('penjualan.index');
    Route::get('/penjualan/{id}', [TransactionController::class, 'reportDetail'])->name('penjualan.show');
    Route::get('/penjualan/print/{id}', [TransactionController::class, 'print'])->name('penjualan.print');

    // Laporan
    Route::get('/laporan', [TransactionController::class, 'report'])->name('laporan.index');
    Route::get('/laporan/{id}', [TransactionController::class, 'reportDetail'])->name('laporan.detail');

    // Reset Keranjang
    Route::get('/clear-cart', function () {
        session()->forget('cart');
        return redirect('/kasir')->with('success', 'Keranjang berhasil dikosongkan!');
    });
});

// Route bantuan perbaikan awal (Hanya dibuka sekali jika perlu reset role)
Route::get('/seed-admin', function () {
    \App\Models\User::updateOrCreate(
        ['email' => 'admin@pos.com'],
        [
            'name'     => 'Super Admin',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'role'     => 'super_admin',
        ]
    );
    return 'Akun Super Admin Berhasil Dibuat!<br>Email: <b>admin@pos.com</b><br>Password: <b>password123</b><br><br><a href="/login">Klik di sini untuk Login</a>';
});