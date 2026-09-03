<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk #{{ $transaction->invoice_number }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            width: 300px;
            margin: 0 auto;
            padding: 10px;
            font-size: 12px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .line { border-bottom: 1px dashed #000; margin: 8px 0; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 3px 0; }
        .no-print { margin-top: 15px; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="text-center">
        <h3 style="margin:0;">TOKO KASIR</h3>
        <p style="margin:2px 0;">Jl. Contoh No. 123</p>
        <p style="margin:2px 0;">Telp: 0812-3456-7890</p>
    </div>

    <div class="line"></div>

    <table style="font-size: 11px;">
        <tr>
            <td>No. Inv</td>
            <td>: {{ $transaction->invoice_number }}</td>
        </tr>
        <tr>
            <td>Waktu</td>
            <td>: {{ $transaction->created_at->format('d/m/Y H:i') }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <table>
        @foreach($transaction->details as $detail)
            <tr>
                <td colspan="2"><strong>{{ $detail->product->name ?? 'Produk Dihapus' }}</strong></td>
            </tr>
            <tr>
                <td>{{ $detail->quantity }} x Rp {{ number_format($detail->price) }}</td>
                <td class="text-right">Rp {{ number_format($detail->subtotal) }}</td>
            </tr>
        @endforeach
    </table>

    <div class="line"></div>

    <table>
        <tr>
            <td><strong>Total</strong></td>
            <td class="text-right"><strong>Rp {{ number_format($transaction->total_price) }}</strong></td>
        </tr>
        <tr>
            <td>Bayar</td>
            <td class="text-right">Rp {{ number_format($transaction->pay_amount) }}</td>
        </tr>
        <tr>
            <td>Kembali</td>
            <td class="text-right">Rp {{ number_format($transaction->return_amount) }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <div class="text-center">
        <p style="margin:5px 0;">-- Terima Kasih --</p>
        <p style="margin:0;">Barang yang sudah dibeli<br>tidak dapat ditukar/dikembalikan</p>
    </div>

    <div class="text-center no-print">
        <button onclick="window.print()" style="padding: 5px 10px; cursor: pointer;">🖨️ Cetak Ulang</button>
        <button onclick="window.close()" style="padding: 5px 10px; cursor: pointer;">Tutup</button>
    </div>

</body>
</html>