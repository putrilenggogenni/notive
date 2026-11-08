@extends('layouts.guest')
@section('title', 'Masuk')

@section('content')
<div class="card-auth">
    <h5 style="font-weight: 500; letter-spacing: -0.3px; margin-bottom: 4px;">Selamat datang kembali</h5>
    <p style="font-size: 13px; color: #999; margin-bottom: 24px;">Masuk untuk melanjutkan jurnal harianmu.</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}" placeholder="nama@email.com" required autofocus>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                placeholder="••••••••" required>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check m-0">
                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember" style="font-size:13px; color:#666;">Ingat saya</label>
            </div>
            @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" style="font-size:13px; color:#5C5BE5;">Lupa password?</a>
            @endif
        </div>

        <button type="submit" class="btn-primary-n">Masuk</button>
    </form>

    <p class="text-center mt-4 mb-0" style="font-size:13px; color:#999;">
        Belum punya akun? <a href="{{ route('register') }}" style="color:#5C5BE5;">Daftar gratis</a>
    </p>
</div>
@endsection