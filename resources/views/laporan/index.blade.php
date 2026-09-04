@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Laporan Penjualan</h2>

    <!-- Form Filter Tanggal -->
    <div class="card p-3 mb-4 shadow-sm">
        <form action="{{ route('laporan.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-bold">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="form-control">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">🔍 Filter Laporan</button>
            </div>
        </form>
    </div>

    <!-- Ringkasan Omzet -->
    <div class="card bg-success text-white p-3 mb-4 shadow-sm">
        <h5>Total Omzet Periode Ini</h5>
        <h2 class="fw-bold mb-0">Rp {{ number_format($totalIncome) }}</h2>
    </div>

    <!-- Tabel Riwayat Transaksi -->
    <div class="card p-3 shadow-sm">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>No. Faktur</th>
                    <th>Tanggal & Waktu</th>
                    <th>Total Belanja</th>
                    <th>Uang Bayar</th>
                    <th>Kembalian</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $index => $trx)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $trx->invoice_number ?? 'INV-'.$trx->id }}</strong></td>
                        <td>{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                        <td>Rp {{ number_format($trx->total_price) }}</td>
                        <td>Rp {{ number_format($trx->pay_amount) }}</td>
                        <td>Rp {{ number_format($trx->return_amount) }}</td>
                       <td class="text-center">
    <a href="{{ route('laporan.detail', $trx->id) }}" class="btn btn-sm btn-info text-white">Detail</a>
    <a href="{{ route('kasir.print', $trx->id) }}" target="_blank" class="btn btn-sm btn-secondary">
        🖨️ Cetak Struk
    </a>
</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Tidak ada data transaksi pada periode tanggal ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection