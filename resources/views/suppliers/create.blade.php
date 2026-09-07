@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h2 class="fw-bold mb-4">Tambah Supplier Baru</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('suppliers.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama Supplier</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="Nama PT/Toko/Supplier">
                </div>

                <div class="mb-3">
                    <label class="form-label">No. Telepon / WA</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx">
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="address" class="form-control" rows="3" placeholder="Alamat lengkap supplier">{{ old('address') }}</textarea>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success me-2">Simpan</button>
                    <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection