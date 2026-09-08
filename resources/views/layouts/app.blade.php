<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir POS System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 text-slate-800 font-sans antialiased flex h-screen overflow-hidden">

    @php
        // Cek apakah user saat ini adalah kasir
        $isKasir = auth()->check() && strtolower(auth()->user()->role) === 'kasir';
    @endphp

    <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col justify-between flex-shrink-0">
        <div>
            <!-- Header Sidebar -->
            <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-800">
                <div class="bg-indigo-600 p-2 rounded-lg text-white">
                    <i class="fa-solid font-bold fa-store text-lg"></i>
                </div>
                <div>
                    <h1 class="font-bold text-white text-base tracking-wide">KASIR SYAHARUDDINFS</h1>
                    <p class="text-xs text-slate-400">POS System</p>
                </div>
            </div>

            <!-- Navigasi Menu -->
            <nav class="p-4 space-y-1 text-sm overflow-y-auto max-h-[calc(100vh-140px)]">
                
                <!-- Dashboard (Tampil untuk Admin / Administrator) -->
                @if(!$isKasir)
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-800 text-slate-300 {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white' : '' }}">
                    <i class="fa-solid fa-border-all w-5"></i> Dashboard
                </a>
                @endif
                
                <!-- KELOMPOK TRANSAKSI -->
                <div class="pt-3 pb-1 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Transaksi</div>
                
                <a href="{{ route('kasir.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-800 text-slate-300 {{ request()->is('kasir*') ? 'bg-indigo-600 text-white' : '' }}">
                    <i class="fa-solid fa-cart-shopping w-5"></i> Kasir
                </a> 

                <!-- Barang Masuk -->
                @if(!$isKasir)
                <a href="{{ route('barang-masuk.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-800 text-slate-300 {{ request()->is('barang-masuk*') ? 'bg-indigo-600 text-white' : '' }}">
                    <i class="fa-solid fa-box-open w-5"></i> Barang Masuk
                </a>
                @endif

                <a href="{{ route('penjualan.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-800 text-slate-300 {{ request()->is('penjualan*') || request()->is('sales*') ? 'bg-indigo-600 text-white' : '' }}">
                    <i class="fa-solid fa-receipt w-5"></i> Penjualan
                </a>

                <!-- Pembayaran & Laporan Penjualan -->
                @if(!$isKasir)
                <a href="{{ route('pembayaran.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-800 text-slate-300 {{ request()->is('pembayaran*') ? 'bg-indigo-600 text-white' : '' }}">
                    <i class="fa-solid fa-wallet w-5"></i> Pembayaran
                </a>

                <a href="{{ route('laporan.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-800 text-slate-300 {{ request()->is('laporan*') ? 'bg-indigo-600 text-white' : '' }}">
                    <i class="fa-solid fa-chart-line w-5"></i> Laporan Penjualan
                </a>
                @endif

                <!-- KELOMPOK MASTER DATA -->
                @if(!$isKasir)
                <div class="pt-3 pb-1 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Master Data</div>
                
                <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-800 text-slate-300 {{ request()->is('products*') || request()->is('produk*') ? 'bg-indigo-600 text-white' : '' }}">
                    <i class="fa-solid fa-box w-5"></i> Produk
                </a>
                <a href="{{ route('categories.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-800 text-slate-300 {{ request()->is('categories*') || request()->is('kategori*') ? 'bg-indigo-600 text-white' : '' }}">
                    <i class="fa-solid fa-tags w-5"></i> Kategori
                </a>
                <a href="{{ route('suppliers.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-800 text-slate-300 {{ request()->is('suppliers*') || request()->is('supplier*') ? 'bg-indigo-600 text-white' : '' }}">
                    <i class="fa-solid fa-truck w-5"></i> Supplier
                </a>
                @endif
            </nav>
        </div>

        <!-- Profil User -->
        <div class="p-4 border-t border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-indigo-500 flex items-center justify-center font-bold text-white">
                    {{ strtoupper(substr(auth()->user()->name ?? 'S', 0, 1)) }}
                </div>
                <div>
                    <p class="text-sm font-semibold text-white leading-tight">{{ auth()->user()->name ?? 'Syahruddin' }}</p>
                    <p class="text-xs text-slate-400 capitalize">{{ auth()->user()->role ?? 'Administrator' }}</p>
                </div>
            </div>
            <i class="fa-solid fa-ellipsis-vertical text-slate-500"></i>
        </div>
    </aside>

    <!-- Konten Utama -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <header class="bg-white border-b border-slate-200 px-6 py-3 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <button class="text-slate-500 hover:text-slate-700"><i class="fa-solid fa-bars text-lg"></i></button>
                <h2 class="font-bold text-slate-800 text-lg">Kasir POS</h2>
            </div>
            <div class="flex items-center gap-4 text-sm">
                <div class="bg-slate-100 px-3 py-1.5 rounded-lg text-xs font-medium text-slate-600 flex items-center gap-2">
                    <i class="fa-regular fa-clock"></i>
                    <span>{{ \Carbon\Carbon::now()->isoFormat('D MMMM YYYY - HH:mm') }} WIB</span>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>