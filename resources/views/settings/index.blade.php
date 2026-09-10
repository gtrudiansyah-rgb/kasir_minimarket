@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Pengaturan Toko</h1>
            <p class="text-slate-500 text-sm">Kelola informasi toko, alamat, dan catatan pada nota/struk pembayaran.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-emerald-100 border border-emerald-400 text-emerald-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 max-w-3xl">
        <form action="{{ route('settings.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Nama Toko</label>
                <input type="text" name="nama_toko" value="{{ old('nama_toko', $setting->nama_toko) }}" 
                    class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @error('nama_toko') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">No. HP / WhatsApp</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $setting->no_hp) }}" 
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('no_hp') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Batas Minimal Alert Stok</label>
                    <input type="number" name="min_stok" value="{{ old('min_stok', $setting->min_stok) }}" 
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('min_stok') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Alamat Toko</label>
                <textarea name="alamat" rows="3" 
                    class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('alamat', $setting->alamat) }}</textarea>
                @error('alamat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Catatan Struk / Nota</label>
                <textarea name="catatan_nota" rows="2" 
                    class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Contoh: Barang yang sudah dibeli tidak dapat ditukar/dikembalikan">{{ old('catatan_nota', $setting->catatan_nota) }}</textarea>
                @error('catatan_nota') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium text-sm transition-colors">
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection