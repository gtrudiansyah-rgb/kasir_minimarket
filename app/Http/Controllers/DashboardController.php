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

        // 1. Stat Cards
        $totalOmsetToday = Transaction::whereDate('created_at', $today)->sum('total_price') 
            ?? Transaction::whereDate('created_at', $today)->sum('total_harga') 
            ?? 0;

        $totalTransactionsToday = Transaction::whereDate('created_at', $today)->count();
        $totalProducts = Product::count();

        // 2. Daftar Stok Menipis (<= 5)
        $stokMenipisList = Product::all()->filter(function ($item) {
            $stok = $item->stok ?? $item->stock ?? 0;
            return $stok <= 5;
        });

        // 3. Data Diagram Bulat (Tunai vs Non-Tunai)
        $allTransactions = Transaction::all();
        
        $totalTunai = $allTransactions->filter(function($i) {
            return strtolower($i->payment_method ?? $i->metode_bayar ?? 'tunai') === 'tunai';
        })->sum(fn($i) => $i->total_price ?? $i->total_harga ?? 0);

        $totalNonTunai = $allTransactions->filter(function($i) {
            return strtolower($i->payment_method ?? $i->metode_bayar ?? 'tunai') !== 'tunai';
        })->sum(fn($i) => $i->total_price ?? $i->total_harga ?? 0);

        // 4. Transaksi Terbaru
        $latestTransactions = Transaction::latest()->take(5)->get();

        return view('dashboard', compact(
            'totalOmsetToday',
            'totalTransactionsToday',
            'totalProducts',
            'stokMenipisList',
            'totalTunai',
            'totalNonTunai',
            'latestTransactions'
        ));
    }
}