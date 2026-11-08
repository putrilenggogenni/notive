@php
    if (auth()->check()) {
        redirect()->route('dashboard')->send();
        exit;
    }
@endphp

@extends('layouts.app')
@section('title', 'About Notive')

@section('content')
<div class="container py-5" style="max-width: 680px;">

    <p class="section-label mb-3">Tentang</p>
    <h1 style="font-family: 'Lora', serif; font-size: clamp(28px, 4vw, 40px); font-weight: 500; letter-spacing: -1px; line-height: 1.2; margin-bottom: 36px;">
        Kenapa Notive ada?
    </h1>

    <div class="prose">
        <p>Di tengah dunia yang serba cepat dan penuh distraksi, kita jarang punya waktu untuk duduk, diam, dan bertanya kepada diri sendiri: hari ini bagaimana?</p>
        <p>Notive lahir dari kebutuhan sederhana, sebuah tempat yang tenang untuk mencatat perjalanan hidup sehari-hari. Bukan untuk dibagikan. Bukan untuk mendapat likes. Tapi untuk dirimu sendiri.</p>
        <p>Terinspirasi dari keindahan jurnal tulis tangan yang dikombinasikan dengan kemudahan teknologi modern, Notive menghadirkan pengalaman menulis yang bersih, fokus, dan menyenangkan.</p>
        <p>Tulis entri harian dengan foto dan mood, tandai dengan tag, lacak streak menulismu, dan temukan kembali cerita-cerita lama melalui pencarian dan filter yang mudah digunakan.</p>
        <p>Teruntuk kamu yang percaya bahwa menulis adalah salah satu cara terbaik untuk mengenal diri sendiri...</p>
    </div>

    <div class="row g-3 mt-4">
        <div class="col-md-6">
            <div class="stat-card">
                <p style="font-size: 14px; font-weight: 500; margin-bottom: 4px;">Privat sepenuhnya</p>
                <p style="font-size: 13px; color: #999; margin: 0; line-height: 1.6;">Entri hanya bisa dilihat oleh kamu sendiri. Tidak ada yang lain.</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-card">
                <p style="font-size: 14px; font-weight: 500; margin-bottom: 4px;">Tampilan bersih</p>
                <p style="font-size: 13px; color: #999; margin: 0; line-height: 1.6;">Desain minimalis yang tidak mengganggu proses menulis.</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-card">
                <p style="font-size: 14px; font-weight: 500; margin-bottom: 4px;">Foto dan mood</p>
                <p style="font-size: 13px; color: #999; margin: 0; line-height: 1.6;">Lampirkan foto dan catat suasana hati di setiap entri.</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-card">
                <p style="font-size: 14px; font-weight: 500; margin-bottom: 4px;">Statistik penulisan</p>
                <p style="font-size: 13px; color: #999; margin: 0; line-height: 1.6;">Lacak streak, mood bulanan, dan perjalanan menulismu.</p>
            </div>
        </div>
    </div>

    @guest
    <div class="text-center mt-5">
        <a href="{{ route('register') }}" class="btn-primary-n" style="padding: 12px 32px; font-size: 15px;">Mulai menulis sekarang</a>
    </div>
    @endguest

</div>
@endsection