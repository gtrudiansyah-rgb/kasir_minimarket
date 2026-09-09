<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangMasuk;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Support\Facades\Schema;
use Exception;

class BarangMasukController extends Controller
{
    public function index(Request $request)
    {
        $suppliers = Supplier::all();
        $products = Product::all();

        $query = BarangMasuk::query();

        // Muat relasi jika ada
        if (method_exists(BarangMasuk::class, 'product')) {
            $query->with('product');
        }
        if (method_exists(BarangMasuk::class, 'supplier')) {
            $query->with('supplier');
        }

        $barangMasuk = $query->orderBy('id', 'desc')->get();

        return view('barang_masuk.index', compact('suppliers', 'products', 'barangMasuk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'quantity'   => 'required|numeric|min:1',
        ]);

        try {
            $bm = new BarangMasuk();
            $bm->product_id = $request->product_id;

            if ($request->filled('supplier_id')) {
                $bm->supplier_id = $request->supplier_id;
            }

            // Isi kolom jumlah/quantity
            if (Schema::hasColumn('barang_masuks', 'quantity')) {
                $bm->quantity = $request->quantity;
            }
            if (Schema::hasColumn('barang_masuks', 'jumlah')) {
                $bm->jumlah = $request->quantity;
            }

            // Isi kolom tanggal
            if (Schema::hasColumn('barang_masuks', 'tanggal_masuk')) {
                $bm->tanggal_masuk = $request->tanggal_masuk ?? now();
            }
            if (Schema::hasColumn('barang_masuks', 'date')) {
                $bm->date = $request->tanggal_masuk ?? now();
            }

            // Isi catatan
            if (Schema::hasColumn('barang_masuks', 'catatan')) {
                $bm->catatan = $request->catatan;
            }

            $bm->save();

            // Update stok produk
            $product = Product::find($request->product_id);
            if ($product) {
                if (Schema::hasColumn('products', 'stock')) {
                    $product->increment('stock', $request->quantity);
                } elseif (Schema::hasColumn('products', 'stok')) {
                    $product->increment('stok', $request->quantity);
                }
            }

            return redirect()->back()->with('success', 'Stok barang masuk berhasil ditambahkan!');

        } catch (Exception $e) {
            return redirect()->back()->withErrors(['db_error' => 'Error Database: ' . $e->getMessage()])->withInput();
        }
    }
}