@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Detail Transaksi: {{ $transaction->invoice_number }}</h4>
        <a href="{{ route('laporan.index') }}" class="btn btn-secondary">Kembali ke Laporan</a>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-1"><strong>Tanggal:</strong> {{ $transaction->created_at->format('d/m/Y H:i') }}</p>
                    <p class="mb-1"><strong>Total Belanja:</strong> Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1"><strong>Bayar:</strong> Rp {{ number_format($transaction->pay_amount, 0, ',', '.') }}</p>
                    <p class="mb-1"><strong>Kembalian:</strong> Rp {{ number_format($transaction->return_amount, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <strong>Rincian Item Barang</strong>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Harga Satuan</th>
                        <th>Jumlah (Qty)</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaction->details as $index => $detail)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $detail->product->name ?? 'Produk Dihapus' }}</td>
                            <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                            <td>{{ $detail->quantity }}</td>
                            <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection