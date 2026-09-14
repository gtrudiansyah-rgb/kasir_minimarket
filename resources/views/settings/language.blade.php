@extends('layouts.app')

@section('content')
<div class="container-fluid">
    {{-- Gunakan fungsi __('...') --}}
    <h1 class="h3 mb-4 text-gray-800">{{ __('Halaman Pengaturan Bahasa') }}</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ __(session('success')) }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('settings.language.update') }}" method="POST">
                @csrf

                <div class="form-group mb-3">
                    <label for="language"><strong>{{ __('Pilih Bahasa Aplikasi') }}</strong></label>
                    <select name="language" id="language" class="form-control">
                        <option value="id" {{ session('locale') == 'id' ? 'selected' : '' }}>Bahasa Indonesia</option>
                        <option value="en" {{ session('locale') == 'en' ? 'selected' : '' }}>English (Inggris)</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">{{ __('Simpan Perubahan') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection