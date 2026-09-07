<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir POS System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 text-slate-800 font-sans antialiased flex h-screen overflow-hidden">

    <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col justify-between flex-shrink-0">
        <div>
            <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-800">
                <div class="bg-indigo-600 p-2 rounded-lg text-white">
                    <i class="fa-solid font-bold fa-store text-lg"></i>
                </div>
                <div>
                    <h1 class="font-bold text-white text-base tracking-wide">KASIR SYAHARUDDINFS</h1>
                    <p class="text-xs text-slate-400">POS System</p>
                </div>
            </div>

            <nav class="p-4 space-y-1 text-sm overflow-y-auto max-h-[calc(100vh-140px)]">
                
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                  <i class="fa-solid fa-border-all me-2"></i> Dashboard
               </a>
                
                <div class="pt-3 pb-1 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Transaksi</div>
                <a href="{{ route('kasir.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-800 text-slate-300 {{ request()->is('kasir*') ? 'bg-indigo-600 text-white' : '' }}">
                    <i class="fa-solid fa-cart-shopping w-5"></i> Kasir
                </a> 
                <a href="{{ route('penjualan.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-800 text-slate-300 {{ request()->is('penjualan*') || request()->is('sales*') ? 'bg-indigo-600 text-white' : '' }}">
                     <i class="fa-solid fa-receipt w-5"></i> Penjualan
                </a>
                <a href="{{ route('pembayaran.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-800 text-slate-300 {{ request()->is('pembayaran*') ? 'bg-indigo-600 text-white' : '' }}">
                     <i class="fa-solid fa-wallet w-5"></i> Pembayaran
                </a>

                <a href="{{ route('laporan.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-800 text-slate-300 {{ request()->is('laporan*') ? 'bg-indigo-600 text-white' : '' }}">
                  <i class="fa-solid fa-chart-line w-5"></i> Laporan Penjualan
                </a>


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
            </nav>
        </div>

        <div class="p-4 border-t border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-indigo-500 flex items-center justify-center font-bold text-white">S</div>
                <div>
                    <p class="text-sm font-semibold text-white leading-tight">Syahruddin</p>
                    <p class="text-xs text-slate-400">Administrator</p>
                </div>
            </div>
            <i class="fa-solid fa-ellipsis-vertical text-slate-500"></i>
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <header class="bg-white border-b border-slate-200 px-6 py-3 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <button class="text-slate-500 hover:text-slate-700"><i class="fa-solid fa-bars text-lg"></i></button>
                <h2 class="font-bold text-slate-800 text-lg">Kasir POS</h2>
            </div>
            <div class="flex items-center gap-4 text-sm">
                <button class="relative text-slate-500 hover:text-slate-700">
                    <i class="fa-bell fa-regular text-lg"></i>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold">3</span>
                </button>
                <div class="bg-slate-100 px-3 py-1.5 rounded-lg text-xs font-medium text-slate-600 flex items-center gap-2">
                    <i class="fa-regular fa-clock"></i>
                    <span>Selasa, 3 Juni 2026 - 10:45</span>
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