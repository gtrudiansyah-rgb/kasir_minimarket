@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h2 class="fw-bold mb-4">Edit Produk</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('products.update', $product->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Kode Produk -->
                <div class="mb-3">
                    <label class="form-label">Kode Produk</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code', $product->code) }}" required>
                </div>

                <!-- Nama Produk -->
                <div class="mb-3">
                    <label class="form-label">Nama Produk</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $product->name ?? $product->nama) }}" required>
                </div>

                <!-- Kategori -->
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
                                {{ $category->nama ?? $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Harga Beli -->
                <div class="mb-3">
                    <label class="form-label">Harga Beli</label>
                    <input type="number" name="purchase_price" class="form-control" value="{{ old('purchase_price', $product->purchase_price) }}" min="0" required>
                </div>

                <!-- Harga Jual -->
                <div class="mb-3">
                    <label class="form-label">Harga Jual</label>
                    <input type="number" name="selling_price" class="form-control" value="{{ old('selling_price', $product->selling_price ?? $product->price) }}" min="0" required>
                </div>

                <!-- Stok -->
                <div class="mb-3">
                    <label class="form-label">Stok</label>
                    <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock ?? $product->stok) }}" min="0" required>
                </div>

                <!-- Tombol Aksi -->
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary me-2">Update Produk</button>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection