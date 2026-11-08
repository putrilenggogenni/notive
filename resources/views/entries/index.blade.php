@extends('layouts.app')
@section('title', 'Semua Entri')

@section('content')
<div class="container py-4" style="max-width: 780px;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 style="font-weight: 500; letter-spacing: -0.5px; margin: 0;">Semua Entri</h4>
        <a href="{{ route('entries.create') }}" class="btn-primary-n">Tulis Baru</a>
    </div>

    <form method="GET" action="{{ route('entries.index') }}" class="mb-4">
        <div class="row g-2">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Cari entri..."
                    value="{{ request('search') }}" style="border-radius: 8px; font-size: 14px;">
            </div>
            <div class="col-md-3">
                <select name="mood" class="form-select" style="border-radius: 8px; font-size: 14px;">
                    <option value="">Semua Mood</option>
                    @foreach(['Tenang','Senang','Bersemangat','Lelah','Sedih','Marah'] as $mood)
                        <option value="{{ $mood }}" {{ request('mood') == $mood ? 'selected' : '' }}>{{ $mood }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="sort" class="form-select" style="border-radius: 8px; font-size: 14px;">
                    <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                    <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>A - Z</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn-primary-n w-100" style="padding: 8px;">Cari</button>
                @if(request()->hasAny(['search','mood','sort','tag']))
                    <a href="{{ route('entries.index') }}" class="btn-outline-n w-100 text-center" style="padding: 8px;">Reset</a>
                @endif
            </div>
        </div>

        @if($tags->count())
        <div class="d-flex gap-2 flex-wrap mt-3">
            @foreach($tags as $tag)
                <a href="{{ route('entries.index', array_merge(request()->except('tag'), ['tag' => $tag->name])) }}"
                   class="tag-pill {{ request('tag') == $tag->name ? 'active' : '' }}"
                   style="{
                   { request('tag') == $tag->name ? 'background: #EDEDFC; color: #5C5BE5;' : '' }}">
                    #{{ $tag->name }}
                </a>
            @endforeach
        </div>
        @endif
    </form>

    @php
        $moodColors = [
            'Tenang' => '#5DCAA5', 'Senang' => '#FAC775',
            'Bersemangat' => '#5C5BE5', 'Lelah' => '#F09595',
            'Sedih' => '#90B8F0', 'Marah' => '#F07070'
        ];
    @endphp

    @forelse($entries as $entry)
    <div class="entry-item">
        @if($entry->photo)
            <img src="{{ asset('storage/' . $entry->photo) }}" class="entry-thumb" alt="{{ $entry->title }}">
        @endif
        <div class="flex-grow-1">
            <div class="d-flex align-items-center gap-2 mb-1">
                @if($entry->mood)
                    <span class="mood-dot" style="background: {{ $moodColors[$entry->mood] ?? '#ccc' }};"></span>
                    <span class="mood-label">{{ $entry->mood }}</span>
                @endif
                <span style="font-size: 11px; color: #bbb;">{{ $entry->created_at->format('d M Y') }}</span>
            </div>
            <a href="{{ route('entries.show', $entry) }}" class="entry-title d-block">{{ $entry->title }}</a>
            <p class="entry-preview">{{ Str::limit(strip_tags($entry->content), 120) }}</p>
            <div class="d-flex gap-1 flex-wrap mt-2">
                @foreach($entry->tags as $tag)
                    <a href="{{ route('entries.index', ['tag' => $tag->name]) }}" class="tag-pill">#{{ $tag->name }}</a>
                @endforeach
            </div>
        </div>
        <div class="d-flex flex-column gap-2 flex-shrink-0">
            <a href="{{ route('entries.edit', $entry) }}" class="btn-outline-n" style="font-size: 12px; padding: 5px 12px; text-align: center;">Edit</a>
            <form method="POST" action="{{ route('entries.destroy', $entry) }}" onsubmit="return confirm('Hapus entri ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-danger-n" style="font-size: 12px; padding: 5px 12px; width: 100%;">Hapus</button>
            </form>
        </div>
    </div>
    @empty
    <div class="text-center py-5" style="color: #999;">
        <p style="margin-bottom: 12px;">Belum ada entri ditemukan.</p>
        <a href="{{ route('entries.create') }}" class="btn-primary-n" style="padding: 8px 20px;">Tulis entri pertama</a>
    </div>
    @endforelse

    <div class="d-flex justify-content-center mt-4">
        {{ $entries->links() }}
    </div>

</div>
@endsection