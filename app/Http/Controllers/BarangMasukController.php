<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class BarangMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = BarangMasuk::with(['supplier', 'product']);

        // Filter Pencarian Keyword (Otomatis mendeteksi struktur kolom database)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                // Pencarian Supplier
                $q->whereHas('supplier', function ($s) use ($search) {
                    $supplierCols = array_filter(['name', 'nama', 'nama_supplier'], fn($col) => Schema::hasColumn('suppliers', $col));
                    $s->where(function ($sub) use ($search, $supplierCols) {
                        foreach ($supplierCols as $col) {
                            $sub->orWhere($col, 'like', "%{$search}%");
                        }
                    });
                })
                // Pencarian Produk
                ->orWhereHas('product', function ($p) use ($search) {
                    $productCols = array_filter(['name', 'nama_produk', 'nama'], fn($col) => Schema::hasColumn('products', $col));
                    $p->where(function ($sub) use ($search, $productCols) {
                        foreach ($productCols as $col) {
                            $sub->orWhere($col, 'like', "%{$search}%");
                        }
                    });
                })
                // Pencarian Catatan
                ->orWhere('catatan', 'like', "%{$search}%");
            });
        }

        // Filter Berdasarkan Tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_masuk', $request->tanggal);
        }

        $barangMasuk = $query->latest()->get();
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