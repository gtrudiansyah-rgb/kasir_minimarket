<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Ambil data statistik
        $totalOmsetToday = Transaction::whereDate('created_at', $today)->sum('total_price');
        $totalTransactionsToday = Transaction::whereDate('created_at', $today)->count();
        $totalProducts = Product::count();
        
        // Produk dengan stok 5 atau kurang
        $lowStockProducts = Product::where('stock', '<=', 5)->get();

        return view('dashboard', compact(
            'totalOmsetToday',
            'totalTransactionsToday',
            'totalProducts',
            'lowStockProducts'
        ));
    }
}