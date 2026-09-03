<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
    <h2>Tambah Produk Baru</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.store') }}" method="POST">
        @csrf

        <!-- Kode Produk -->
        <div class="mb-3">
            <label class="form-label">Kode Produk</label>
            <input type="text" name="code" class="form-control" value="{{ old('code') }}" required>
        </div>

        <!-- Nama Produk -->
        <div class="mb-3">
            <label class="form-label">Nama Produk</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <!-- Kategori -->
        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="category_id" class="form-select" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                        {{ $category->nama ?? $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Harga Beli -->
        <div class="mb-3">
            <label class="form-label">Harga Beli</label>
            <input type="number" name="purchase_price" class="form-control" value="{{ old('purchase_price') }}" min="0" required>
        </div>

        <!-- Harga Jual -->
        <div class="mb-3">
            <label class="form-label">Harga Jual</label>
            <input type="number" name="selling_price" class="form-control" value="{{ old('selling_price') }}" min="0" required>
        </div>

        <!-- Stok -->
        <div class="mb-3">
            <label class="form-label">Stok</label>
            <input type="number" name="stock" class="form-control" value="{{ old('stock') }}" min="0" required>
        </div>

        <!-- Tombol Aksi -->
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</body>
</html>