<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category; // Di-import agar bisa dipanggil saat pilih kategori produk
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Tampilkan daftar produk.
     */
    public function index()
    {
        // 'with('category')' digunakan agar nama kategori ikut ter-load (Eager Loading)
        $products = Product::with('category')->latest()->get();
        return view('products.index', compact('products'));
    }

    /**
     * Tampilkan form tambah produk.
     */
    public function create()
    {
        // Ambil semua kategori untuk ditampilkan di pilihan (dropdown) form tambah produk
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    /**
     * Simpan produk baru ke database.
     */
    public function store(Request $request)
{
    $request->validate([
        'code'           => 'required|string|unique:products,code',
        'name'           => 'required|string|max:255',
        'category_id'    => 'required|exists:categories,id',
        'supplier_id'    => 'nullable|exists:suppliers,id',
        'purchase_price' => 'required|numeric|min:0',
        'selling_price'  => 'required|numeric|min:0',
        'stock'          => 'required|integer|min:0',
    ]);

    Product::create([
        'code'           => $request->code,
        'name'           => $request->name,
        'category_id'    => $request->category_id,
        'supplier_id'    => $request->supplier_id,
        'purchase_price' => $request->purchase_price,
        'selling_price'  => $request->selling_price,
        'stock'          => $request->stock,
    ]);

    return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
}

    /**
     * Tampilkan detail produk.
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Tampilkan form edit produk.
     */
    public function edit(Product $product)
    {
        // Ambil kategori untuk pilihan dropdown saat edit
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update data produk di database.
     */
    public function update(Request $request, Product $product)
{
    $request->validate([
        'code'           => 'required|string|unique:products,code,' . $product->id,
        'name'           => 'required|string|max:255',
        'category_id'    => 'required|exists:categories,id',
        'purchase_price' => 'required|numeric|min:0',
        'selling_price'  => 'required|numeric|min:0',
        'stock'          => 'required|integer|min:0',
    ]);

    $product->update([
        'code'           => $request->code,
        'name'           => $request->name,
        'category_id'    => $request->category_id,
        'purchase_price' => $request->purchase_price,
        'selling_price'  => $request->selling_price,
        'stock'          => $request->stock,
    ]);

    return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
}
    /**
     * Hapus produk dari database.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }
}