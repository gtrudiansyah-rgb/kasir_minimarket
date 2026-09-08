@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <h2 class="mb-4 font-bold">Halaman Kasir</h2>

    <!-- Pesan Sukses / Umum -->
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

    <!-- Alert Transaksi Berhasil -->
    @if(session('transaksi_sukses'))
        @php $trx = session('transaksi_sukses'); @endphp
        <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="alert-heading fw-bold mb-0">
                    <i class="bi bi-check-circle-fill me-2"></i>Transaksi Berhasil!
                </h5>
                @if(isset($trx['print_id']))
                    <a href="{{ route('kasir.print', $trx['print_id']) }}" target="_blank" class="btn btn-sm btn-outline-success fw-bold">
                        🖨️ Cetak Struk
                    </a>
                @endif
            </div>
            <hr class="my-2">
            <div class="row text-center mt-3">
                <div class="col-4 border-end">
                    <span class="d-block fw-semibold text-dark small">Total Belanja</span>
                    <h5 class="fw-bold mb-0 text-dark">Rp {{ number_format($trx['total'], 0, ',', '.') }}</h5>
                </div>
                <div class="col-4 border-end">
                    <span class="d-block fw-semibold text-dark small">Uang Dibayar</span>
                    <h5 class="fw-bold mb-0 text-primary">Rp {{ number_format($trx['bayar'], 0, ',', '.') }}</h5>
                </div>
                <div class="col-4">
                    <span class="d-block fw-semibold text-dark small">Kembalian</span>
                    <h4 class="fw-bold mb-0 text-success">Rp {{ number_format($trx['kembalian'], 0, ',', '.') }}</h4>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- KOLOM KIRI: SCAN & DAFTAR PRODUK -->
        <div class="col-md-7">
            <!-- Form Scan Barcode -->
            <form action="{{ route('kasir.add') }}" method="POST" class="mb-4">
                @csrf
                <div class="input-group">
                    <input type="text" name="code" id="barcode" class="form-control" placeholder="Scan Barcode / Ketik Kode Produk..." autofocus required autocomplete="off">
                    <button class="btn btn-primary" type="submit">Tambah</button>
                </div>
            </form>

            <!-- Grid Produk Dari Database -->
            <h5 class="fw-bold mb-3">Pilih Produk</h5>
            <div class="row row-cols-1 row-cols-md-3 g-3 mb-4">
                @isset($products)
                    @foreach($products as $product)
                    <div class="col">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <h6 class="card-title fw-bold text-dark mb-1">{{ $product->name }}</h6>
                                    <p class="text-primary fw-bold mb-2">Rp {{ number_format($product->selling_price ?? $product->price ?? $product->harga ?? 0, 0, ',', '.') }}</p>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <small class="text-muted">Stok: {{ $product->stock ?? 0 }}</small>
                                    
                                    <!-- Tombol + untuk Menambah ke Keranjang -->
                                    <form action="{{ route('kasir.add') }}" method="POST" class="m-0">
                                        @csrf
                                        <input type="hidden" name="code" value="{{ $product->id }}">
                                        <button type="submit" class="btn btn-sm btn-primary rounded-circle" style="width: 32px; height: 32px; padding: 0;">+</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endisset
            </div>
        </div>

        <!-- KOLOM KANAN: TABEL KERANJANG & CHECKOUT -->
        <div class="col-md-5">
            <div class="card p-3 shadow-sm border-0">
                <h5 class="fw-bold mb-3">Keranjang Belanja</h5>
                
                <table class="table table-bordered align-middle text-sm">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th style="width: 100px;">Qty</th>
                            <th>Subtotal</th>
                            <th style="width: 50px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cart as $id => $item)
                            <tr>
                                <td>
                                    <strong class="d-block">{{ $item['name'] }}</strong>
                                    <small class="text-muted">Rp {{ number_format($item['price'], 0, ',', '.') }}</small>
                                </td>
                                <td>
                                    <!-- Form Update Qty -->
                                    <form action="{{ route('kasir.update') }}" method="POST" class="d-flex align-items-center">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $id }}">
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="form-control form-control-sm me-1" style="width: 55px;">
                                        <button type="submit" class="btn btn-sm btn-outline-primary p-1" title="Update">🔄</button>
                                    </form>
                                </td>
                                <td>Rp {{ number_format($item['subtotal'] ?? ($item['price'] * $item['quantity']), 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <!-- Form Hapus Item -->
                                    <form action="{{ route('kasir.remove', $id) }}" method="POST" onsubmit="return confirm('Hapus produk?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger p-1">🗑️</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">Keranjang kosong. Klik produk atau scan barcode.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Form Checkout -->
                @if(!empty($cart))
                    <div class="border-top pt-3 mt-2">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-bold fs-5">Total:</span>
                            <span class="fw-bold fs-4 text-primary">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>

                        <form action="{{ route('kasir.checkout') }}" method="POST">
                            @csrf
                            
                            <!-- Pilihan Metode Pembayaran -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Metode Pembayaran</label>
                                <select name="payment_method" id="paymentMethod" class="form-select" required>
                                    <option value="cash" selected>Cash / Tunai</option>
                                    <option value="qris">QRIS</option>
                                </select>
                            </div>

                            <!-- Input Uang Bayar -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Uang Bayar (Rp)</label>
                                <input type="number" name="pay_amount" id="payAmount" class="form-control form-control-lg" placeholder="Masukkan nominal" required min="{{ $total }}">
                            </div>

                            <button type="submit" class="btn btn-success btn-lg w-100 fw-bold">Bayar & Simpan Transaksi</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- TABEL TRANSAKSI HARI INI (BARU DISEDIAKAN) -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold py-3 d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-clock-history me-2 text-primary"></i>Transaksi Hari Ini</span>
                    <span class="badge bg-primary rounded-pill">{{ isset($todayTransactions) ? count($todayTransactions) : 0 }} Transaksi</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 text-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>No. Invoice</th>
                                    <th>Waktu</th>
                                    <th>Metode Bayar</th>
                                    <th>Total Belanja</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($todayTransactions) && count($todayTransactions) > 0)
                                    @foreach($todayTransactions as $trx)
                                        <tr>
                                            <td>
                                                <span class="fw-bold text-dark">{{ $trx->invoice_number ?? $trx->invoice ?? ('INV-' . $trx->id) }}</span>
                                            </td>
                                            <td>{{ $trx->created_at ? $trx->created_at->format('H:i') . ' WIB' : '-' }}</td>
                                            <td>
                                                <span class="badge {{ strtolower($trx->payment_method ?? '') === 'qris' ? 'bg-success' : 'bg-info text-dark' }}">
                                                    {{ strtoupper($trx->payment_method ?? 'CASH') }}
                                                </span>
                                            </td>
                                            <td class="fw-bold text-primary">Rp {{ number_format($trx->total_price ?? $trx->total ?? 0, 0, ',', '.') }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('kasir.print', $trx->id) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                                    🖨️ Struk
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">Belum ada transaksi untuk hari ini.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('barcode').focus();

    // Jalankan logika pilihan metode pembayaran
    const paymentMethod = document.getElementById('paymentMethod');
    const payAmount = document.getElementById('payAmount');
    const totalAmount = {{ $total ?? 0 }};

    if (paymentMethod) {
        paymentMethod.addEventListener('change', function() {
            if (this.value === 'qris') {
                payAmount.value = totalAmount;
                payAmount.readOnly = true;
            } else {
                payAmount.value = '';
                payAmount.readOnly = false;
                payAmount.focus();
            }
        });
    }
</script>

@if(session('print_id'))
    <script>
        window.open("{{ route('kasir.print', session('print_id')) }}", "_blank");
    </script>
@endif
@endsection