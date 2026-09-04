<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;

class TransactionController extends Controller
{
    // 1. Tampilan Halaman Kasir
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = array_sum(array_column($cart, 'subtotal'));

        return view('kasir.index', compact('cart', 'total'));
    }

    // 2. Tambah Produk ke Keranjang
    public function addProduct(Request $request)
    {
        $product = Product::where('code', $request->code)
            ->orWhere('id', $request->code)
            ->first();

        if (!$product) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan!');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity']++;
            $cart[$product->id]['subtotal'] = $cart[$product->id]['quantity'] * $product->price;
        } else {
            $cart[$product->id] = [
                'name'     => $product->name,
                'price'    => $product->price,
                'quantity' => 1,
                'subtotal' => $product->price,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan!');
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

        if ($payAmount < $totalPrice) {
            return redirect()->back()->with('error', 'Uang bayar kurang dari total belanja!');
        }

        // Cek kecukupan stok sebelum diproses
        foreach ($cart as $id => $item) {
            $product = Product::find($id);
            if (!$product || $product->stok < $item['quantity']) {
                return redirect()->back()->with('error', "Stok '{$item['name']}' tidak mencukupi! Sisa stok: " . ($product->stok ?? 0));
            }
        }

        // Simpan data transaksi utama
        $transaction = Transaction::create([
            'total_price'   => $totalPrice,
            'pay_amount'    => $payAmount,
            'return_amount' => $payAmount - $totalPrice,
        ]);

        // Potong stok produk otomatis di database
        foreach ($cart as $id => $item) {
            $product = Product::find($id);
            if ($product) {
                $product->decrement('stok', $item['quantity']);
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

    // 7. Halaman Laporan Penjualan
    public function report(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-d'));

        $transactions = Transaction::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->latest()
            ->get();

        $totalIncome = $transactions->sum('total_price');

        return view('laporan.index', compact('transactions', 'totalIncome', 'startDate', 'endDate'));
    }
}