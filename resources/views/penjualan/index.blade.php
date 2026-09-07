@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <h2 class="fw-bold mb-3">Riwayat Penjualan</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <table class="table table-bordered bg-white mb-0">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>No. Invoice</th>
                        <th>Tanggal & Waktu</th>
                        <th>Total Belanja</th>
                        <th>Metode Bayar</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penjualan ?? $sales ?? [] as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><span class="badge bg-secondary">{{ $item->invoice_number ?? $item->no_faktur }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</td>
                            <td>Rp {{ number_format($item->total_price ?? $item->total_harga ?? 0, 0, ',', '.') }}</td>
                            <td>{{ strtoupper($item->payment_method ?? 'TUNAI') }}</td>
                            <td>
                                <a href="{{ route('penjualan.show', $item->id) }}" class="btn btn-info btn-sm text-white">Detail</a>
                                <a href="{{ route('penjualan.print', $item->id) }}" target="_blank" class="btn btn-secondary btn-sm">Struk</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada riwayat transaksi penjualan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection