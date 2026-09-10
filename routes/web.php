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
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController; // <-- Ditambahkan
use App\Http\Middleware\AdminOnly;

// ROUTE GUEST (Belum Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// ROUTE USER (Sudah Login)
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Route yang BISA DIAKSES Kasir & Admin
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    Route::get('/kasir', [TransactionController::class, 'index'])->name('kasir.index');
    Route::post('/kasir/add', [TransactionController::class, 'addProduct'])->name('kasir.add');
    Route::post('/kasir/update', [TransactionController::class, 'updateCart'])->name('kasir.update');
    Route::post('/kasir/remove/{id}', [TransactionController::class, 'removeCart'])->name('kasir.remove');
    Route::post('/kasir/checkout', [TransactionController::class, 'checkout'])->name('kasir.checkout');
    Route::get('/kasir/print/{id}', [TransactionController::class, 'print'])->name('kasir.print');

    Route::get('/penjualan', function () {
        $penjualan = \App\Models\Transaction::latest()->get();
        return view('penjualan.index', compact('penjualan'));
    })->name('penjualan.index');
    Route::get('/penjualan/{id}', [TransactionController::class, 'reportDetail'])->name('penjualan.show');
    Route::get('/penjualan/print/{id}', [TransactionController::class, 'print'])->name('penjualan.print');

    Route::get('/clear-cart', function () {
        session()->forget('cart');
        return redirect('/kasir')->with('success', 'Keranjang berhasil dikosongkan!');
    });

    // ROUTE KHUSUS ADMIN / SUPER ADMIN (Kasir Ditolak)
    Route::middleware(AdminOnly::class)->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Master Data
        Route::resource('categories', CategoryController::class);
        Route::resource('suppliers', SupplierController::class);
        Route::resource('products', ProductController::class);
        Route::resource('users', UserController::class);

        // Pengaturan Toko <-- Ditambahkan
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

        // Barang Masuk & Pembayaran
        Route::get('/barang-masuk', [BarangMasukController::class, 'index'])->name('barang-masuk.index');
        Route::post('/barang-masuk', [BarangMasukController::class, 'store'])->name('barang-masuk.store');
        Route::get('/pembayaran', [TransactionController::class, 'pembayaran'])->name('pembayaran.index');

        // Laporan
        Route::get('/laporan', [TransactionController::class, 'report'])->name('laporan.index');
        Route::get('/laporan/{id}', [TransactionController::class, 'reportDetail'])->name('laporan.detail');
    });
});

// Seed Admin
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