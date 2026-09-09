<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kasir POS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-slate-900 flex items-center justify-center min-h-screen p-4 font-sans antialiased">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden">
        <!-- Header Form -->
        <div class="bg-indigo-600 p-8 text-center text-white">
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4 backdrop-blur-sm">
                <i class="fa-solid fa-store text-3xl"></i>
            </div>
            <h1 class="text-2xl font-bold uppercase tracking-wider">KASIR POS</h1>
            <p class="text-indigo-200 text-sm mt-1">Masuk untuk mengakses sistem kasir</p>
        </div>

        <!-- Form Login -->
        <div class="p-8">
            @if(session('success'))
                <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-lg flex items-center gap-2">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-3 bg-rose-50 border border-rose-200 text-rose-700 text-sm rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-2">Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <i class="fa-solid fa-envelope"></i>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com"
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-indigo-600 focus:bg-white transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-2">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input type="password" name="password" required placeholder="••••••••"
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-indigo-600 focus:bg-white transition">
                    </div>
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-slate-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded text-indigo-600 focus:ring-0">
                        <span>Ingat Saya</span>
                    </label>
                </div>

                <button type="submit" 
                    class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition duration-200 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    <span>Masuk Ke Sistem</span>
                </button>
            </form>
        </div>
    </div>

</body>
</html>