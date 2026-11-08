@extends('layouts.app')
@section('title', 'Profil')

@section('content')
<div class="container py-4" style="max-width: 560px;">

    <h4 style="font-weight: 500; letter-spacing: -0.5px; margin-bottom: 4px;">Pengaturan Profil</h4>
    <p style="font-size: 14px; color: #999; margin-bottom: 32px;">Kelola informasi akun Notive kamu.</p>

    <div class="card-n p-4 mb-3">
        <h6 style="font-weight: 500; margin-bottom: 20px;">Informasi Akun</h6>
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf @method('PATCH')

            <div class="mb-3">
                <label style="font-size: 13px; color: #555; display: block; margin-bottom: 6px;">Nama</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', auth()->user()->name) }}" style="border-radius: 8px; font-size: 14px;">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
                <label style="font-size: 13px; color: #555; display: block; margin-bottom: 6px;">Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email', auth()->user()->email) }}" style="border-radius: 8px; font-size: 14px;">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn-primary-n" style="padding: 9px 20px;">Simpan Perubahan</button>
        </form>
    </div>

    <div class="card-n p-4 mb-3">
        <h6 style="font-weight: 500; margin-bottom: 20px;">Ubah Password</h6>
        <form method="POST" action="{{ route('password.update') }}">
            @csrf @method('PUT')

            <div class="mb-3">
                <label style="font-size: 13px; color: #555; display: block; margin-bottom: 6px;">Password Saat Ini</label>
                <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" style="border-radius: 8px; font-size: 14px;">
                @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label style="font-size: 13px; color: #555; display: block; margin-bottom: 6px;">Password Baru</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" style="border-radius: 8px; font-size: 14px;">
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
                <label style="font-size: 13px; color: #555; display: block; margin-bottom: 6px;">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" class="form-control" style="border-radius: 8px; font-size: 14px;">
            </div>

            <button type="submit" class="btn-primary-n" style="padding: 9px 20px;">Ubah Password</button>
        </form>
    </div>

    <div class="card-n p-4" style="border-color: #f5c6cb;">
        <h6 style="font-weight: 500; color: #c0392b; margin-bottom: 8px;">Hapus Akun</h6>
        <p style="font-size: 13px; color: #999; margin-bottom: 16px;">Akun dan seluruh entri akan dihapus permanen.</p>
        <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Hapus akun secara permanen?')">
            @csrf @method('DELETE')
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Masukkan password untuk konfirmasi" style="border-radius: 8px; font-size: 14px;">
            </div>
            <button type="submit" class="btn-danger-n">Hapus Akun Saya</button>
        </form>
    </div>

</div>
@endsection