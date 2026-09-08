@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <h2 class="fw-bold mb-3">Data Produk</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('products.create') }}" class="btn btn-primary">Tambah Produk</a>

        <!-- Input pencarian tanpa tombol submit/form -->
        <input type="text" id="searchInput" class="form-control w-25" placeholder="Cari nama produk...">
    </div>

    <table class="table table-bordered bg-white" id="productTable">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $index => $product)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $product->nama ?? $product->name }}</td>
                    <td>Rp {{ number_format($product->selling_price ?? $product->price ?? 0, 0, ',', '.') }}</td>
                    <td>{{ $product->stok ?? $product->stock ?? 0 }}</td>
                    <td>
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada data produk</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Script pencarian langsung (Real-time tanpa reload) -->
<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    let keyword = this.value.toLowerCase();
    let rows = document.querySelectorAll('#productTable tbody tr');

    rows.forEach(row => {
        // Mengambil teks dari kolom "Nama Produk" (index ke-1)
        let productName = row.children[1] ? row.children[1].textContent.toLowerCase() : '';
        
        if (productName.includes(keyword)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
@endsection