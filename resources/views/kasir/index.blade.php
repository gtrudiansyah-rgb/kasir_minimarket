@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Halaman Kasir</h2>

    <!-- Pesan Sukses / Kembalian -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Pesan Gagal / Error -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <strong>Gagal!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Form Scan Barcode / Input Produk -->
    <form action="{{ route('kasir.add') }}" method="POST" class="mb-4">
        @csrf
        <div class="input-group">
            <input type="text" name="code" id="barcode" class="form-control" placeholder="Scan Barcode / Ketik Kode Produk..." autofocus required>
            <button class="btn btn-primary" type="submit">Tambah</button>
        </div>
    </form>

    <!-- Tabel Keranjang Belanja -->
    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th style="width: 180px;">Jumlah</th>
                <th>Subtotal</th>
                <th style="width: 100px;" class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cart as $id => $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>Rp {{ number_format($item['price']) }}</td>
                    <td>
                        <!-- Form Ubah Jumlah -->
                        <form action="{{ route('kasir.cart.update') }}" method="POST" class="d-flex align-items-center">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $id }}">
                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="form-control form-control-sm me-2" style="width: 80px;">
                            <button type="submit" class="btn btn-sm btn-outline-primary" title="Update Jumlah">🔄</button>
                        </form>
                    </td>
                    <td>Rp {{ number_format($item['subtotal'] ?? ($item['price'] * $item['quantity'])) }}</td>
                    <td class="text-center">
                        <!-- Form Hapus Item -->
                        <form action="{{ route('kasir.cart.remove', $id) }}" method="POST" onsubmit="return confirm('Hapus produk ini dari keranjang?')">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger">🗑️ Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">Keranjang masih kosong. Silakan scan barcode.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if(session('transaksi_sukses'))
    @php $trx = session('transaksi_sukses'); @endphp
    <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="alert-heading fw-bold mb-0">
                <i class="bi bi-check-circle-fill me-2"></i>Transaksi Berhasil!
            </h5>
            @if(isset($trx['print_id']))
                <a href="{{ route('kasir.print', $trx['print_id']) }}" target="_blank" class="btn btn-sm btn-outline-success fw-bold">
                    <i class="bi bi-printer me-1"></i> Cetak Struk
                </a>
            @endif
        </div>
        <hr class="my-2">
        <div class="row text-center mt-3">
            <div class="col-md-4 border-end">
                <span class="text-muted small d-block">Total Belanja</span>
                <h5 class="fw-bold mb-0">Rp {{ number_format($trx['total'], 0, ',', '.') }}</h5>
            </div>
            <div class="col-md-4 border-end">
                <span class="text-muted small d-block">Uang Dibayar</span>
                <h5 class="fw-bold mb-0 text-primary">Rp {{ number_format($trx['bayar'], 0, ',', '.') }}</h5>
            </div>
            <div class="col-md-4">
                <span class="text-muted small d-block">Kembalian</span>
                <h4 class="fw-bold mb-0 text-success">Rp {{ number_format($trx['kembalian'], 0, ',', '.') }}</h4>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif



    <script>
        document.getElementById('barcode').focus();
    </script>

    @if(session('print_id'))
        <script>
            // Otomatis buka struk di TAB BARU tanpa meninggalkan halaman kasir
            window.open("{{ route('kasir.print', session('print_id')) }}", "_blank");
        </script>
    @endif
@endsection
