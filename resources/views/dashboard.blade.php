@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <h4 class="fw-bold mb-3">Dashboard Utama</h4>

    <!-- Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white shadow-sm border-0 rounded-3">
                <div class="card-body">
                    <small class="text-white-50">Omset Hari Ini</small>
                    <h3 class="fw-bold mb-0">Rp {{ number_format($totalOmsetToday, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white shadow-sm border-0 rounded-3">
                <div class="card-body">
                    <small class="text-white-50">Transaksi Hari Ini</small>
                    <h3 class="fw-bold mb-0">{{ $totalTransactionsToday }} Transaksi</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white shadow-sm border-0 rounded-3">
                <div class="card-body">
                    <small class="text-white-50">Total Jenis Produk</small>
                    <h3 class="fw-bold mb-0">{{ $totalProducts }} Produk</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Peringatan Stok Menipis (Warna-Warni Dynamic) -->
<div class="card border-0 shadow-sm mb-4 rounded-3 overflow-hidden">
    <div class="card-header {{ $stokMenipisList->count() > 0 ? 'bg-danger' : 'bg-success' }} text-white fw-bold py-3 d-flex justify-content-between align-items-center">
        <span>Peringatan Stok Menipis (&le; 3)</span>
        <span class="badge bg-white {{ $stokMenipisList->count() > 0 ? 'text-danger' : 'text-success' }} rounded-pill px-3 py-2 fs-6">
            {{ $stokMenipisList->count() }} Produk
        </span>
    </div>
    <div class="card-body p-0">
        @if($stokMenipisList->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Kode</th>
                            <th>Nama Produk</th>
                            <th>Harga Jual</th>
                            <th class="text-center">Sisa Stok</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stokMenipisList as $item)
                            @php
                                $harga = 0;
                                foreach (['harga_jual', 'harga', 'price', 'selling_price', 'harga_produk'] as $field) {
                                    if (isset($item->$field) && $item->$field > 0) {
                                        $harga = $item->$field;
                                        break;
                                    }
                                }
                                $stok = $item->stok ?? $item->stock ?? 0;
                            @endphp
                            <tr>
                                <td class="ps-3 fw-bold">{{ $item->id ?? $item->code ?? $item->kode }}</td>
                                <td class="fw-semibold">{{ $item->nama ?? $item->name ?? $item->nama_produk }}</td>
                                <td>Rp {{ number_format($harga, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    @if($stok == 0)
                                        <span class="badge bg-danger rounded-pill px-3 py-2">0 Item</span>
                                    @elseif($stok <= 2)
                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2">{{ $stok }} Item</span>
                                    @else
                                        <span class="badge bg-info text-dark rounded-pill px-3 py-2">{{ $stok }} Item</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($stok == 0)
                                        <span class="badge bg-danger text-uppercase px-2 py-1">Habis</span>
                                    @elseif($stok <= 2)
                                        <span class="badge bg-warning text-dark text-uppercase px-2 py-1">Sangat Kritis</span>
                                    @else
                                        <span class="badge bg-info text-dark text-uppercase px-2 py-1">Menipis</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <!-- Tampilan Saat Semua Stok Aman -->
            <div class="p-4 text-center bg-success-subtle text-success fw-bold">
                Semua stok produk dalam kondisi aman dan mencukupi!
            </div>
        @endif
    </div>
</div>

    <!-- Layout Diagram & Transaksi Terakhir -->
    <div class="row g-3">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm h-100 rounded-3">
                <div class="card-header bg-white fw-bold py-3">Proporsi Pembayaran</div>
                <div class="card-body d-flex justify-content-center align-items-center" style="height: 250px;">
                    <canvas id="paymentPieChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden">
                <div class="card-header bg-white fw-bold py-3">Transaksi Terakhir</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">No. Invoice</th>
                                    <th>Waktu</th>
                                    <th>Metode</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestTransactions as $item)
                                    <tr>
                                        <td class="ps-3 font-monospace fw-semibold">{{ $item->invoice_number ?? $item->no_faktur }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</td>
                                        <td><span class="badge bg-secondary">{{ strtoupper($item->payment_method ?? $item->metode_bayar ?? 'TUNAI') }}</span></td>
                                        <td class="fw-bold text-success">Rp {{ number_format($item->total_price ?? $item->total_harga ?? 0, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3 text-muted">Belum ada transaksi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('paymentPieChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Tunai', 'Non-Tunai / QRIS'],
            datasets: [{
                data: [{{ $totalTunai }}, {{ $totalNonTunai }}],
                backgroundColor: ['#0d6efd', '#198754']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });
</script>
@endsection