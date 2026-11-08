@extends('layouts.guest')
@section('title', 'Daftar')

@section('content')
<div class="card-auth">
    <h5 style="font-weight: 500; letter-spacing: -0.3px; margin-bottom: 4px;">Buat akun baru</h5>
    <p style="font-size: 13px; color: #999; margin-bottom: 24px;">Mulai jurnal harianmu hari ini.</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name') }}" placeholder="Nama lengkapmu" required autofocus>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}" placeholder="nama@email.com" required>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                placeholder="Minimal 8 karakter" required>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-4">
            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control"
                placeholder="Ulangi password" required>
        </div>

        <button type="submit" class="btn-primary-n">Buat Akun</button>
    </form>

    <p class="text-center mt-4 mb-0" style="font-size:13px; color:#999;">
        Sudah punya akun? <a href="{{ route('login') }}" style="color:#5C5BE5;">Masuk</a>
    </p>
</div>
@endsection