@extends('layouts.app')
@section('title', 'Ruang untuk pikiran yang jujur')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 text-center py-5">
            <p class="section-label mb-3">Personal Journal</p>
            <h1 style="font-family: 'Lora', serif; font-size: clamp(32px, 5vw, 50px); font-weight: 500; letter-spacing: -1.5px; line-height: 1.2;" class="mb-3">
                Ruang untuk<br>pikiran yang jujur.
            </h1>
            <p style="font-size: 17px; color: #666; line-height: 1.7; max-width: 420px; margin: 0 auto 32px;">
                Tulis, simpan, dan renungkan hari-harimu. Hanya untukmu — dengan foto, suasana hati, dan cerita.
            </p>
            <div class="d-flex gap-3 justify-content-center">
                <a href="{{ route('register') }}" class="btn-primary-n" style="padding: 10px 24px; font-size: 15px;">Mulai sekarang</a>
                <a href="{{ route('about') }}" class="btn-outline-n" style="padding: 10px 24px; font-size: 15px;">Pelajari lebih lanjut</a>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-4 justify-content-center">
        <div class="col-md-4">
            <div class="stat-card">
                <p style="font-size: 15px; font-weight: 500; margin-bottom: 6px;">Tulis setiap hari</p>
                <p style="font-size: 14px; color: #666; margin: 0; line-height: 1.6;">Rekam momen, perasaan, dan pikiran dalam satu tempat yang aman dan privat.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <p style="font-size: 15px; font-weight: 500; margin-bottom: 6px;">Lampirkan foto</p>
                <p style="font-size: 14px; color: #666; margin: 0; line-height: 1.6;">Setiap cerita lebih hidup dengan gambar. Upload foto untuk melengkapi entri harimu.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <p style="font-size: 15px; font-weight: 500; margin-bottom: 6px;">Lacak perjalananmu</p>
                <p style="font-size: 14px; color: #666; margin: 0; line-height: 1.6;">Lihat streak menulis, statistik mood, dan perkembanganmu dari bulan ke bulan.</p>
            </div>
        </div>
    </div>
</div>
@endsection