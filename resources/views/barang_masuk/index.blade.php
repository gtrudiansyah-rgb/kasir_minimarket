@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-slate-800">Data Barang Masuk (Restok)</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-header bg-indigo-600 text-white font-bold py-3">
                    <i class="fa-solid fa-plus-circle me-1"></i> Input Stok Masuk
                </div>
                <div class="card-body">

                    {{-- Blok Notifikasi Error Validasi --}}
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                            <strong class="d-block mb-1"><i class="fa-solid fa-triangle-exclamation me-1"></i> Gagal Menyimpan:</strong>
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('barang-masuk.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label font-semibold text-slate-700">Tanggal Masuk</label>
                            <input type="date" name="tanggal_masuk" class="form-control" value="{{ old('tanggal_masuk', date('Y-m-d')) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-semibold text-slate-700">Supplier</label>
                            <select name="supplier_id" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Supplier --</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name ?? $supplier->nama_supplier ?? $supplier->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-semibold text-slate-700">Produk</label>
                            <select name="product_id" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Produk --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name ?? $product->nama_produk }} (Stok: {{ $product->stock ?? $product->stok ?? 0 }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-semibold text-slate-700">Jumlah Masuk (Qty)</label>
                            <input type="number" name="quantity" class="form-control" placeholder="0" min="1" value="{{ old('jumlah') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-semibold text-slate-700">Catatan (Opsional)</label>
                            <textarea name="catatan" class="form-control" rows="2" placeholder="Contoh: Pengiriman No. PO 123">{{ old('catatan') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 font-bold bg-indigo-600 hover:bg-indigo-700 border-0">
                            Simpan & Tambah Stok
                        </button>
                    </form>
                </div>
            </div>
        </div>

        
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-header bg-white font-bold py-3 text-slate-800 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <i class="fa-solid fa-clock-rotate-left me-1"></i> Riwayat Penerimaan Barang
                    </div>
                    
                    
                    <form action="{{ route('barang-masuk.index') }}" method="GET" class="d-flex gap-2">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari supplier / produk..." value="{{ request('search') }}">
                        <input type="date" name="tanggal" class="form-control form-control-sm" value="{{ request('tanggal') }}">
                        <button type="submit" class="btn btn-sm btn-primary bg-indigo-600 border-0">
                            <i class="fa-solid fa-magnifying-glass"></i> Cari
                        </button>
                        @if(request('search') || request('tanggal'))
                            <a href="{{ route('barang-masuk.index') }}" class="btn btn-sm btn-secondary">Reset</a>
                        @endif
                    </form>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-slate-50 text-slate-600">
                                <tr>
                                    <th class="px-3 py-2">No</th>
                                    <th class="px-3 py-2">Hari, Tanggal</th>
                                    <th class="px-3 py-2">Supplier</th>
                                    <th class="px-3 py-2">Produk</th>
                                    <th class="px-3 py-2">Jumlah</th>
                                    <th class="px-3 py-2">Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($barangMasuk as $index => $item)
                                    <tr>
                                        <td class="px-3 py-2">{{ $index + 1 }}</td>
                                        <td class="px-3 py-2">
                                            {{ \Carbon\Carbon::parse($item->tanggal_masuk)->locale('id')->isoFormat('dddd, DD/MM/YYYY') }}
                                        </td>
                                        <td class="px-3 py-2 font-semibold text-slate-700">
                                            {{ $item->supplier->name ?? $item->supplier->nama_supplier ?? $item->supplier->nama ?? '-' }}
                                        </td>
                                        <td class="px-3 py-2">{{ $item->product->name ?? $item->product->nama_produk ?? '-' }}</td>
                                        <td class="px-3 py-2">
                                            <span class="badge bg-success">+{{ $item->jumlah }}</span>
                                        </td>
                                        <td class="px-3 py-2 text-slate-500">{{ $item->catatan ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-slate-400">Belum ada riwayat barang masuk.</td>
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
@endsection