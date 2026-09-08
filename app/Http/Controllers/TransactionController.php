<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;

class TransactionController extends Controller
{
    // 1. Tampilan Halaman Kasir (Kirim $products & $todayTransactions ke Blade)
    public function index()
    {
        $products = Product::all();
        $cart = session()->get('cart', []);
        $total = array_sum(array_column($cart, 'subtotal'));

        // Mengambil transaksi khusus HARI INI untuk tabel kasir
        $todayTransactions = Transaction::whereDate('created_at', Carbon::today())
            ->latest()
            ->get();

        return view('kasir.index', compact('products', 'cart', 'total', 'todayTransactions'));
    }

    // 2. Tambah Produk ke Keranjang (Support ID, Barcode, Code, & Multi-Kolom Harga)
    public function addProduct(Request $request)
    {
        $code = $request->code;

        // Mulai pencarian dari ID
        $query = Product::where('id', $code);

        // Cek apakah kolom 'code' ada di tabel 'products'
        if (\Illuminate\Support\Facades\Schema::hasColumn('products', 'code')) {
            $query->orWhere('code', $code);
        }

        // Cek apakah kolom 'barcode' ada di tabel 'products'
        if (\Illuminate\Support\Facades\Schema::hasColumn('products', 'barcode')) {
            $query->orWhere('barcode', $code);
        }

        $product = $query->first();

        if (!$product) {
            return redirect()->back()->with('error', 'Produk dengan kode/ID "' . $code . '" tidak ditemukan!');
        }

        // Ambil harga barang
        $price = $product->selling_price ?? $product->price ?? $product->harga ?? 0;

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity']++;
            $cart[$product->id]['subtotal'] = $cart[$product->id]['quantity'] * $price;
        } else {
            $cart[$product->id] = [
                'name'     => $product->name,
                'price'    => $price,
                'quantity' => 1,
                'subtotal' => $price,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Produk ' . $product->name . ' berhasil ditambahkan!');
    }

    // 3. Update Jumlah Keranjang
    public function updateCart(Request $request)
    {
        $cart = session()->get('cart', []);
        $productId = $request->product_id;
        $quantity = $request->quantity;

        if (isset($cart[$productId])) {
            if ($quantity > 0) {
                $cart[$productId]['quantity'] = $quantity;
                $cart[$productId]['subtotal'] = $cart[$productId]['price'] * $quantity;
                session()->put('cart', $cart);
                return redirect()->back()->with('success', 'Jumlah produk diperbarui!');
            } else {
                unset($cart[$productId]);
                session()->put('cart', $cart);
                return redirect()->back()->with('success', 'Produk dihapus!');
            }
        }

        return redirect()->back()->with('error', 'Produk tidak ditemukan!');
    }

    // 4. Hapus 1 Item Produk dari Keranjang
    public function removeCart($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang!');
    }

    // 5. Proses Checkout & Potong Stok Otomatis
    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Keranjang belanja masih kosong!');
        }

        // Hitung total belanja
        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        $payAmount = $request->pay_amount;

        if (!$payAmount || $payAmount < $totalPrice) {
            return redirect()->back()->with('error', 'Uang bayar kurang dari total belanja!');
        }

        // Cek kecukupan stok sebelum diproses
        foreach ($cart as $id => $item) {
            $product = Product::find($id);
            if (!$product || $product->stock < $item['quantity']) {
                return redirect()->back()->with('error', "Stok '{$item['name']}' tidak mencukupi! Sisa stok: " . ($product->stock ?? 0));
            }
        }

        // Tangkap metode pembayaran (cash/qris), default 'cash'
        $paymentMethod = $request->input('payment_method', 'cash');

        // Simpan data transaksi utama ke database
        $transaction = Transaction::create([
            'invoice_number' => 'INV-' . date('YmdHis'),
            'total_price'    => $totalPrice,
            'pay_amount'     => $payAmount,
            'return_amount'  => $payAmount - $totalPrice,
            'payment_method' => $paymentMethod,
        ]);

        // Potong stok produk otomatis di database
        foreach ($cart as $id => $item) {
            $product = Product::find($id);
            if ($product) {
                $product->decrement('stock', $item['quantity']);
            }
        }

        // Bersihkan keranjang belanja
        session()->forget('cart');

        return redirect()->route('kasir.index')->with('transaksi_sukses', [
            'total'     => $transaction->total_price,
            'bayar'     => $transaction->pay_amount,
            'kembalian' => $transaction->return_amount,
            'print_id'  => $transaction->id,
        ]);
    }

    // 6. Cetak Struk
    public function print($id)
    {
        $transaction = Transaction::findOrFail($id);
        return view('kasir.print', compact('transaction'));
    }

    // 7. Halaman Laporan Penjualan (Khusus Admin)
    public function report(Request $request)
    {
        if (auth()->check() && strtolower(auth()->user()->role) === 'kasir') {
            return redirect()->route('kasir.index')->with('error', 'Akses ditolak! Halaman ini khusus Admin.');
        }

        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-d'));

        $transactions = Transaction::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->latest()
            ->get();

        $totalIncome = $transactions->sum('total_price');

        return view('laporan.index', compact('transactions', 'totalIncome', 'startDate', 'endDate'));
    }

    public function reportDetail($id)
    {
        $transaction = Transaction::with(['details.product'])->findOrFail($id);
        return view('laporan.detail', compact('transaction'));
    }

    // 8. Halaman Riwayat Penjualan (Kasir hanya melihat hari ini, Admin melihat semua)
    public function history()
    {
        $query = Transaction::latest();

        // Jika role Kasir, filter transaksi HARI INI saja
        if (auth()->check() && strtolower(auth()->user()->role) === 'kasir') {
            $query->whereDate('created_at', Carbon::today());
        }

        $penjualan = $query->get(); 
        return view('penjualan.index', compact('penjualan'));
    }

    // 9. Halaman Data & Metode Pembayaran (Khusus Admin)
    public function pembayaran()
    {
        // Blokir akses jika user bertindak sebagai Kasir
        if (auth()->check() && strtolower(auth()->user()->role) === 'kasir') {
            return redirect()->route('kasir.index')->with('error', 'Akses ditolak! Halaman ini khusus Admin.');
        }

        $transactions = Transaction::latest()->get();

        // Menghitung total transaksi tunai (mencakup 'cash' dan 'tunai')
        $totalTunai = Transaction::whereIn(DB::raw('LOWER(payment_method)'), ['cash', 'tunai'])->sum('total_price');

        // Menghitung total transaksi QRIS
        $totalQris = Transaction::where(DB::raw('LOWER(payment_method)'), 'qris')->sum('total_price');

        return view('pembayaran.index', compact('transactions', 'totalTunai', 'totalQris'));
    }
}