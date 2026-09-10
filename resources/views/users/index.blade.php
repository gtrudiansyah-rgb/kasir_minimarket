@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 font-weight-bold text-gray-800">Kelola User</h1>
            <p class="text-muted small mb-0">Manajemen akun pengguna, nomor HP kasir, dan hak akses sistem</p>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
            <i class="fa-solid fa-user-plus me-1"></i> Tambah User Baru
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3" style="width: 5%">#</th>
                            <th class="py-3">Nama</th>
                            <th class="py-3">Email</th>
                            <th class="py-3">No. HP / WhatsApp</th>
                            <th class="py-3">Role</th>
                            <th class="py-3 text-center" style="width: 15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                            <tr>
                                <td class="px-4 font-semibold text-slate-500">{{ $loop->iteration }}</td>
                                <td class="font-semibold text-slate-800">
                                    <div class="d-flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-circle bg-indigo-100 text-indigo-600 d-flex items-center justify-center font-bold text-sm">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <span>{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="text-slate-600">{{ $user->email }}</td>
                                
                                <!-- KOLOM NO HP KASIR -->
                                <td>
                                    @if($user->no_hp)
                                        @php
                                            // Format nomor WA dari 08... jadi 628...
                                            $formattedWa = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $user->no_hp));
                                        @endphp
                                        <div class="d-flex items-center gap-2">
                                            <span class="font-mono text-slate-700">{{ $user->no_hp }}</span>
                                            <a href="https://wa.me/{{ $formattedWa }}" target="_blank" class="btn btn-sm btn-outline-success py-0 px-2 rounded-pill" title="Chat WhatsApp">
                                                <i class="fa-brands fa-whatsapp"></i> Chat
                                            </a>
                                        </div>
                                    @else
                                        <span class="text-muted fst-italic text-xs">Belum diisi</span>
                                    @endif
                                </td>

                                <td>
                                    @if(in_array(strtolower($user->role), ['super_admin', 'admin']))
                                        <span class="badge bg-purple-100 text-purple-700 px-2.5 py-1 rounded-full text-xs font-semibold">SUPER ADMIN</span>
                                    @elseif(strtolower($user->role) === 'manager')
                                        <span class="badge bg-blue-100 text-blue-700 px-2.5 py-1 rounded-full text-xs font-semibold">MANAGER</span>
                                    @else
                                        <span class="badge bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full text-xs font-semibold">KASIR</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-warning me-1" data-bs-toggle="modal" data-bs-target="#modalEditUser{{ $user->id }}">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    @if(auth()->id() !== $user->id)
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>

                            <!-- MODAL EDIT USER -->
                            <div class="modal fade" id="modalEditUser{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('users.update', $user->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title font-bold">Edit Data User</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label font-semibold">Nama Lengkap</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label font-semibold">Email</label>
                                                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                                                </div>
                                                
                                                <!-- INPUT NO HP KASIR -->
                                                <div class="mb-3">
                                                    <label class="form-label font-semibold">No. HP / WhatsApp Kasir</label>
                                                    <input type="text" name="no_hp" class="form-control" value="{{ $user->no_hp }}" placeholder="Contoh: 081267933813">
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label font-semibold">Role</label>
                                                    <select name="role" class="form-select" required>
                                                        <option value="kasir" {{ strtolower($user->role) === 'kasir' ? 'selected' : '' }}>Kasir</option>
                                                        <option value="manager" {{ strtolower($user->role) === 'manager' ? 'selected' : '' }}>Manager</option>
                                                        <option value="super_admin" {{ in_array(strtolower($user->role), ['super_admin', 'admin']) ? 'selected' : '' }}>Super Admin</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label font-semibold">Password <small class="text-muted">(Kosongkan jika tidak diubah)</small></label>
                                                    <input type="password" name="password" class="form-control" placeholder="••••••••">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada data user.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH USER -->
<div class="modal fade" id="modalTambahUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title font-bold">Tambah User Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label font-semibold">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" placeholder="Masukkan nama..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-semibold">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="contoh@pos.com" required>
                    </div>
                    
                    <!-- INPUT NO HP KASIR BARU -->
                    <div class="mb-3">
                        <label class="form-label font-semibold">No. HP / WhatsApp</label>
                        <input type="text" name="no_hp" class="form-control" placeholder="Contoh: 081267933813">
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-semibold">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="kasir">Kasir</option>
                            <option value="manager">Manager</option>
                            <option value="super_admin">Super Admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-semibold">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan User</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection