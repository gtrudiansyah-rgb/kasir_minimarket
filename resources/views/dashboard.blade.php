@extends('layouts.app') {{-- Sesuaikan nama layout utama Anda --}}

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Dashboard Utama</h2>

    <!-- Ringkasan Kartu Statistik -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body">
                    <h6>Omset Hari Ini</h6>
                    <h3>Rp {{ number_format($totalOmsetToday, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body">
                    <h6>Transaksi Hari Ini</h6>
                    <h3>{{ $totalTransactionsToday }} Transaksi</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white shadow-sm">
                <div class="card-body">
                    <h6>Total Jenis Produk</h6>
                    <h3>{{ $totalProducts }} Produk</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Peringatan Stok Menipis -->
    <div class="card shadow-sm">
        <div class="card-header bg-danger text-white">
            <strong>Peringatan Stok Menipis (<= 5)</strong>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Produk</th>
                        <th>Harga Jual</th>
                        <th>Sisa Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lowStockProducts as $product)
                        <tr>
                            <td><code>{{ $product->code }}</code></td>
                            <td>{{ $product->name }}</td>
                            <td>Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                            <td><span class="badge bg-danger">{{ $product->stock }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">Semua stok produk masih aman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection