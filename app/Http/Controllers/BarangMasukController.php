<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class BarangMasukController extends Controller
{
    public function index()
    {
        $barangMasuk = BarangMasuk::with(['supplier', 'product'])->latest()->get();
        $products = Product::all();
        $suppliers = Supplier::all();

        return view('barang_masuk.index', compact('barangMasuk', 'products', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id'   => 'required',
            'product_id'    => 'required',
            'jumlah'        => 'required|numeric|min:1',
            'tanggal_masuk' => 'required|date',
        ]);

        BarangMasuk::create([
            'supplier_id'   => $request->supplier_id,
            'product_id'    => $request->product_id,
            'jumlah'        => $request->jumlah,
            'tanggal_masuk' => $request->tanggal_masuk,
            'catatan'       => $request->catatan,
        ]);

        $product = Product::findOrFail($request->product_id);
        
        if (isset($product->stock)) {
            $product->increment('stock', $request->jumlah);
        } else {
            $product->increment('stok', $request->jumlah);
        }

        return redirect()->back()->with('success', 'Stok produk berhasil ditambahkan!');
    }
}