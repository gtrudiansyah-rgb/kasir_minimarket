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
    // 2. Tambah Produk ke Keranjang
    public function addProduct(Request $request)
    {
        $product = Product::where('code', $request->code)
                    ->orWhere('id', $request->code)
                    ->first();

        if (!$product) {
            return redirect()->back()->with('error', 'Kode produk tidak ditemukan!');
        }

        // Mengambil harga asli dari kolom database 'selling_price'
        $price = $product->selling_price;

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity']++;
            $cart[$product->id]['subtotal'] = $price * $cart[$product->id]['quantity'];
        } else {
            $cart[$product->id] = [
                'name'     => $product->name,
                'price'    => $price,
                'quantity' => 1,
                'subtotal' => $price
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
            return redirect()->back()->with('success', 'Produk berhasil dihapus!');
        }

        return redirect()->back()->with('error', 'Produk tidak ditemukan!');
    }

    // 5. Simpan Transaksi Pembayaran
    public function store(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Keranjang masih kosong!');
        }

        $totalPrice = array_sum(array_column($cart, 'subtotal'));
        $payAmount = $request->pay_amount;

        if ($payAmount < $totalPrice) {
            return redirect()->back()->with('error', 'Uang bayar kurang!');
        }

        $transaction = Transaction::create([
            'invoice_number' => 'INV-' . time(),
            'total_price'   => $totalPrice,
            'pay_amount'     => $payAmount,
            'return_amount'  => $payAmount - $totalPrice,
        ]);

        session()->forget('cart');

        return redirect()->route('kasir.print', $transaction->id);
    }

    // 6. Cetak Struk Transaksi
    public function print($id)
    {
        $transaction = Transaction::findOrFail($id);

        return view('kasir.print', compact('transaction'));
    }

    // 7. Laporan Penjualan
    public function report(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-d'));

        $transactions = Transaction::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->latest()
            ->get();

        $totalIncome = $transactions->sum('total_price');

        return view('laporan.index', compact('transactions', 'startDate', 'endDate', 'totalIncome'));
    }
}