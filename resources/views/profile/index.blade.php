@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <h1 class="text-2xl font-bold text-slate-800 mb-4">Profil Saya</h1>

    {{-- Notifikasi Berhasil --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Notifikasi Error --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <strong>Gagal!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- Kartu Informasi Akun User --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-lg text-center p-4">
                <div class="w-20 h-20 bg-indigo-600 text-white rounded-circle d-flex align-items-center justify-content-center fs-2 fw-bold mx-auto mb-3">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
                <h5 class="fw-bold text-slate-800 mb-1">{{ auth()->user()->name }}</h5>
                <p class="text-muted mb-2">{{ auth()->user()->email }}</p>
                <div>
                    <span class="badge bg-indigo-100 text-indigo-700 uppercase px-3 py-1">
                        {{ auth()->user()->role ?? 'User' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Form Ubah Password --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-header bg-white font-bold py-3 text-slate-800">
                    <i class="fa-solid fa-key me-1"></i> Ubah Kata Sandi
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.password.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label font-semibold text-slate-700">Password Saat Ini</label>
                            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-semibold text-slate-700">Password Baru</label>
                            <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror" required>
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-semibold text-slate-700">Konfirmasi Password Baru</label>
                            <input type="password" name="new_password_confirmation" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary bg-indigo-600 hover:bg-indigo-700 border-0 font-bold px-4">
                            Simpan Password Baru
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection