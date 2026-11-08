@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="container py-4">

    <div class="mb-4">
        <h4 style="font-weight: 500; letter-spacing: -0.5px; margin-bottom: 4px;">Halo, {{ auth()->user()->name }}</h4>
        <p style="font-size: 14px; color: #999; margin: 0;">{{ now()->translatedFormat('l, d F Y') }}</p>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-val">{{ $totalEntries }}</div>
                <div class="stat-label">Total Entri</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-val">{{ $thisMonth }}</div>
                <div class="stat-label">Bulan Ini</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-val" style="color: #5C5BE5;">{{ $streak }}</div>
                <div class="stat-label">Hari Streak</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-val">{{ $moodData->count() }}</div>
                <div class="stat-label">Jenis Mood</div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="stat-card">
                <p class="section-label mb-3">Entri 6 Bulan Terakhir</p>
                <canvas id="monthlyChart" height="110"></canvas>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="stat-card h-100">
                <p class="section-label mb-3">Breakdown Mood</p>
                @php
                    $moodColors = [
                        'Tenang' => '#5DCAA5', 'Senang' => '#FAC775',
                        'Bersemangat' => '#5C5BE5', 'Lelah' => '#F09595',
                        'Sedih' => '#90B8F0', 'Marah' => '#F07070'
                    ];
                @endphp
                @forelse($moodData as $mood => $count)
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <div class="d-flex align-items-center gap-2">
                            <span class="mood-dot" style="background: {{ $moodColors[$mood] ?? '#ccc' }};"></span>
                            <span style="font-size: 13px;">{{ $mood }}</span>
                        </div>
                        <span style="font-size: 12px; color: #999;">{{ $count }}x</span>
                    </div>
                    <div class="progress mb-3" style="height: 3px; border-radius: 4px; background: #f0f0f0;">
                        <div class="progress-bar" style="width: {{ $totalEntries > 0 ? ($count/$totalEntries)*100 : 0 }}%; background: {{ $moodColors[$mood] ?? '#ccc' }};"></div>
                    </div>
                @empty
                    <p style="font-size: 13px; color: #999;">Belum ada data mood.</p>
                @endforelse
            </div>
        </div>
    </div>

   {{-- ===== KALENDER ===== --}}
<div class="stat-card mb-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <p class="section-label m-0">Kalender Entri</p>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('dashboard', ['cal_month' => $prevMonth->month, 'cal_year' => $prevMonth->year]) }}"
               style="color: #999; text-decoration: none; font-size: 18px; line-height: 1;">&lsaquo;</a>
            <span style="font-size: 13px; font-weight: 500; min-width: 100px; text-align: center;">
                {{ $calendarDate->translatedFormat('F Y') }}
            </span>
            <a href="{{ route('dashboard', ['cal_month' => $nextMonth->month, 'cal_year' => $nextMonth->year]) }}"
               style="color: #999; text-decoration: none; font-size: 18px; line-height: 1;">&rsaquo;</a>
        </div>
    </div>

    @php
        $calMoodColors = [
            'Tenang'      => '#5DCAA5',
            'Senang'      => '#FAC775',
            'Bersemangat' => '#5C5BE5',
            'Lelah'       => '#F09595',
            'Sedih'       => '#90B8F0',
            'Marah'       => '#F07070',
        ];
    @endphp

    <div class="nv-calendar">
        {{-- Header hari --}}
        @foreach(['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $day)
            <div class="nv-cal-header">{{ $day }}</div>
        @endforeach

        {{-- Padding awal bulan --}}
        @for($i = 0; $i < $firstDayOfWeek; $i++)
            <div class="nv-cal-cell nv-cal-empty"></div>
        @endfor

        {{-- Isi tanggal --}}
        @for($day = 1; $day <= $daysInMonth; $day++)
            @php
                $dateStr    = sprintf('%04d-%02d-%02d', $calendarYear, $calendarMonth, $day);
                $dayData    = $entryDates[$dateStr] ?? null;
                $hasEntry   = !is_null($dayData);
                $count      = $hasEntry ? $dayData->count : 0;
                $mood       = $hasEntry ? $dayData->mood : null;
                $moodColor  = $calMoodColors[$mood] ?? '#C4956A';
                $isToday    = $dateStr === now()->toDateString();
                $firstId    = $entriesByDate[$dateStr] ?? null;
            @endphp

            @if($hasEntry && $firstId)
                {{-- Bisa diklik, arah ke entries filter tanggal --}}
                <a href="{{ route('entries.index', ['date' => $dateStr]) }}"
                   class="nv-cal-cell nv-cal-has-entry {{ $isToday ? 'nv-cal-today' : '' }}"
                   style="background: {{ $moodColor }}22; text-decoration: none;"
                   title="{{ $count }} entri · {{ $mood ?? '' }}">
                    <span class="nv-cal-day-num" style="color: {{ $moodColor }};">{{ $day }}</span>
                    <span class="nv-cal-dot" style="background: {{ $moodColor }};"></span>
                </a>
            @else
                <div class="nv-cal-cell {{ $isToday ? 'nv-cal-today' : '' }}">
                    <span class="nv-cal-day-num">{{ $day }}</span>
                </div>
            @endif
        @endfor
    </div>

    {{-- Legend --}}
    <div class="d-flex flex-wrap align-items-center gap-3 mt-3" style="font-size: 12px; color: #999;">
        @foreach($calMoodColors as $moodName => $color)
            <div class="d-flex align-items-center gap-1">
                <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:{{ $color }};"></span>
                {{ $moodName }}
            </div>
        @endforeach
        <div class="d-flex align-items-center gap-1">
            <span style="display:inline-block;width:8px;height:8px;border-radius:50%;border:2px solid #5C5BE5;"></span>
            Hari ini
        </div>
    </div>
</div>
{{-- ===== END KALENDER ===== --}}

    <div class="d-flex justify-content-between align-items-center mb-3">
        <p class="section-label m-0">Entri Terbaru</p>
        <a href="{{ route('entries.index') }}" style="font-size: 13px; color: #5C5BE5;">Lihat semua</a>
    </div>

    @forelse($recentEntries as $entry)
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
                <span style="font-size: 11px; color: #bbb;">{{ $entry->created_at->diffForHumans() }}</span>
            </div>
            <a href="{{ route('entries.show', $entry) }}" class="entry-title d-block">{{ $entry->title }}</a>
            <p class="entry-preview">{{ Str::limit(strip_tags($entry->content), 100) }}</p>
            <div class="d-flex gap-1 flex-wrap mt-2">
                @foreach($entry->tags as $tag)
                    <span class="tag-pill">#{{ $tag->name }}</span>
                @endforeach
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-5" style="color: #999;">
        <p style="margin-bottom: 12px;">Belum ada entri.</p>
        <a href="{{ route('entries.create') }}" class="btn-primary-n" style="padding: 8px 20px;">Tulis entri pertama</a>
    </div>
    @endforelse

    <div class="text-end mt-4">
        <a href="{{ route('entries.create') }}" class="btn-primary-n" style="padding: 10px 20px;">Tulis Entri Baru</a>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('monthlyChart').getContext('2d');
const monthlyData = @json($monthlyData);
const monthNames = ['','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: monthlyData.map(d => monthNames[d.month]),
        datasets: [{
            label: 'Entri',
            data: monthlyData.map(d => d.total),
            backgroundColor: 'rgba(92,91,229,0.15)',
            borderColor: '#5C5BE5',
            borderWidth: 2,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 11 } }, grid: { color: '#f5f5f5' } },
            x: { grid: { display: false }, ticks: { font: { size: 11 } } }
        }
    }
});
</script>
@endpush
@endsection