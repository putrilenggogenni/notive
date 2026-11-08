@extends('layouts.app')
@section('title', $entry->title)

@section('content')
<div class="container py-4" style="max-width: 680px;">

    <div class="mb-4">
        <a href="{{ route('entries.index') }}" style="font-size: 13px; color: #999; text-decoration: none;">Semua Entri</a>
    </div>

    @if($entry->photo)
        <img src="{{ asset('storage/' . $entry->photo) }}" alt="{{ $entry->title }}" 
        class="w-100 mb-4" style="max-height: 380px; object-fit: cover; border-radius: 10px;">
    @endif

    <div class="d-flex align-items-center gap-3 mb-3">
        @if($entry->mood)
            @php
                $moodColors = [
                    'Tenang' => '#5DCAA5', 'Senang' => '#FAC775',
                    'Bersemangat' => '#5C5BE5', 'Lelah' => '#F09595',
                    'Sedih' => '#90B8F0', 'Marah' => '#F07070'
                ];
            @endphp
            <span class="d-flex align-items-center gap-2" style="font-size: 12px; color: #999;">
                <span class="mood-dot" style="background: {{ $moodColors[$entry->mood] ?? '#ccc' }};"></span>
                {{ $entry->mood }}
            </span>
        @endif
        <span style="font-size: 12px; color: #bbb;">{{ $entry->created_at->translatedFormat('l, d F Y') }}</span>
    </div>

    <h1 style="font-family: 'Lora', serif; font-size: clamp(24px, 4vw, 34px); font-weight: 500; letter-spacing: -0.5px; line-height: 1.25; margin-bottom: 28px;">
        {{ $entry->title }}
    </h1>

    <div class="prose">
        {!! $entry->content !!}
    </div>

    @if($entry->tags->count())
    <div class="d-flex gap-2 flex-wrap mt-4 pt-4" style="border-top: 1px solid #E8E8E8;">
        @foreach($entry->tags as $tag)
            <a href="{{ route('entries.index', ['tag' => $tag->name]) }}" class="tag-pill">#{{ $tag->name }}</a>
        @endforeach
    </div>
    @endif

    <div class="d-flex gap-2 mt-4 pt-3" style="border-top: 1px solid #E8E8E8;">
        <a href="{{ route('entries.edit', $entry) }}" class="btn-outline-n">Edit Entri</a>
        <form method="POST" action="{{ route('entries.destroy', $entry) }}" onsubmit="return confirm('Hapus entri ini?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-danger-n">Hapus</button>
        </form>
    </div>

</div>
@endsection