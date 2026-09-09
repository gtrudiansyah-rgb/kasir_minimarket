@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Kelola User</h1>
            <p class="text-sm text-slate-500">Manajemen akun pengguna dan hak akses sistem</p>
        </div>
        <button data-bs-toggle="modal" data-bs-target="#modalTambahUser" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg flex items-center gap-2 transition shadow-sm">
            <i class="fa-solid fa-user-plus"></i> Tambah User
        </button>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-lg text-sm flex justify-between items-center">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-emerald-700 font-bold">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-700 rounded-r-lg text-sm flex justify-between items-center">
            <span>{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="text-rose-700 font-bold">&times;</button>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3.5 w-16 text-center">#</th>
                        <th class="px-6 py-3.5">Nama</th>
                        <th class="px-6 py-3.5">Email</th>
                        <th class="px-6 py-3.5">Role</th>
                        <th class="px-6 py-3.5 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $index => $user)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 text-center font-medium text-slate-400">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-800">{{ $user->name }}</td>
                        <td class="px-6 py-4">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            @if($user->role === 'super_admin')
                                <span class="bg-purple-100 text-purple-700 text-xs font-semibold px-2.5 py-1 rounded-full uppercase tracking-wider">Super Admin</span>
                            @elseif($user->role === 'manager')
                                <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-full uppercase tracking-wider">Manager</span>
                            @else
                                <span class="bg-emerald-100 text-emerald-700 text-xs font-semibold px-2.5 py-1 rounded-full uppercase tracking-wider">Kasir</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button data-bs-toggle="modal" data-bs-target="#modalEditUser{{ $user->id }}" class="text-slate-500 hover:text-indigo-600 p-1.5 rounded-lg hover:bg-slate-100 transition">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                @if(auth()->id() !== $user->id)
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-500 hover:text-rose-600 p-1.5 rounded-lg hover:bg-slate-100 transition">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>

                    <div class="modal fade" id="modalEditUser{{ $user->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-xl border-0 shadow-lg">
                                <div class="modal-header border-b border-slate-100 px-6 py-4">
                                    <h5 class="modal-title font-bold text-slate-800">Edit User</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('users.update', $user->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body p-6 space-y-4">
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Nama Lengkap</label>
                                            <input type="text" name="name" value="{{ $user->name }}" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Email</label>
                                            <input type="email" name="email" value="{{ $user->email }}" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Password <span class="text-slate-400 font-normal normal-case">(Opsional)</span></label>
                                            <input type="password" name="password" minlength="6" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none" placeholder="Isi jika ingin ubah password">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Role / Peran</label>
                                            <select name="role" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                                <option value="super_admin" {{ $user->role === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                                <option value="manager" {{ $user->role === 'manager' ? 'selected' : '' }}>Manager</option>
                                                <option value="kasir" {{ $user->role === 'kasir' ? 'selected' : '' }}>Kasir</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-t border-slate-100 px-6 py-3">
                                        <button type="button" class="px-4 py-2 text-sm text-slate-600 hover:bg-slate-100 rounded-lg" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="px-4 py-2 text-sm bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-400">Belum ada data user.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-xl border-0 shadow-lg">
            <div class="modal-header border-b border-slate-100 px-6 py-4">
                <h5 class="modal-title font-bold text-slate-800">Tambah User Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="modal-body p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Nama Lengkap</label>
                        <input type="text" name="name" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none" placeholder="Masukkan nama lengkap">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Email</label>
                        <input type="email" name="email" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none" placeholder="nama@pos.com">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Password</label>
                        <input type="password" name="password" required minlength="6" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none" placeholder="Minimal 6 karakter">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Role / Peran</label>
                        <select name="role" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <option value="kasir">Kasir</option>
                            <option value="manager">Manager</option>
                            <option value="super_admin">Super Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-t border-slate-100 px-6 py-3">
                    <button type="button" class="px-4 py-2 text-sm text-slate-600 hover:bg-slate-100 rounded-lg" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700">Simpan User</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection