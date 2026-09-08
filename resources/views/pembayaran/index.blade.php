@extends('layouts.app')

@section('content')
@php
    // Hitung total tunai ('cash' / 'tunai') & non-tunai ('qris')
    $totalTunai = $transactions->filter(function($i) {
        $method = strtolower($i->payment_method ?? $i->metode_bayar ?? 'cash');
        return in_array($method, ['cash', 'tunai']);
    })->sum(fn($i) => $i->total_price ?? $i->total_harga ?? 0);

    $totalNonTunai = $transactions->filter(function($i) {
        $method = strtolower($i->payment_method ?? $i->metode_bayar ?? '');
        return $method === 'qris';
    })->sum(fn($i) => $i->total_price ?? $i->total_harga ?? 0);
@endphp

<div class="container-fluid py-4">
    <h2 class="fw-bold mb-3">Data & Metode Pembayaran</h2>

    <!-- Card Ringkasan -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body">
                    <h6 class="text-white-50">Pembayaran Tunai</h6>
                    <h3 class="fw-bold">Rp {{ number_format($totalTunai, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body">
                    <h6 class="text-white-50">Pembayaran Non-Tunai / QRIS</h6>
                    <h3 class="fw-bold">Rp {{ number_format($totalNonTunai, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Rincian Pembayaran -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <table class="table table-bordered bg-white mb-0">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>No. Invoice</th>
                        <th>Tanggal</th>
                        <th>Metode Bayar</th>
                        <th>Total Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><span class="badge bg-secondary">{{ $item->invoice_number ?? $item->no_faktur }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</td>
                            <td>
                                @php
                                    $method = strtolower($item->payment_method ?? $item->metode_bayar ?? 'cash');
                                @endphp
                                @if($method === 'qris')
                                    <span class="badge bg-success">QRIS</span>
                                @else
                                    <span class="badge bg-info text-dark">TUNAI</span>
                                @endif
                            </td>
                            <td>Rp {{ number_format($item->total_price ?? $item->total_harga ?? 0, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada data pembayaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection