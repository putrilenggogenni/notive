<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Notive') }} — @yield('title', 'Personal Journal')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;1,400&family=Inter:wght@400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --accent: #5C5BE5;
            --accent-light: #EDEDFC;
            --border: #E8E8E8;
            --muted: #999;
            --text: #1A1A1A;
            --bg: #FAFAFA;
        }

        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); font-size: 15px; }

        /* Navbar */
        .site-nav { background: var(--bg); border-bottom: 1px solid var(--border); padding: 14px 0; position: sticky; top: 0; z-index: 100; }
        .site-nav .brand { font-size: 19px; font-weight: 500; letter-spacing: -0.5px; color: var(--text); text-decoration: none; }
        .site-nav .brand span { color: var(--accent); }
        .site-nav a.nav-link { font-size: 14px; color: var(--muted); padding: 0; }
        .site-nav a.nav-link:hover { color: var(--text); }

        /* Buttons */
        .btn-primary-n { background: var(--text); color: white; border: none; border-radius: 6px; font-size: 13px; padding: 7px 16px; text-decoration: none; display: inline-block; }
        .btn-primary-n:hover { background: #333; color: white; }
        .btn-outline-n { background: transparent; color: var(--text); border: 1px solid var(--border); border-radius: 6px; font-size: 13px; padding: 7px 16px; text-decoration: none; display: inline-block; }
        .btn-outline-n:hover { border-color: #999; color: var(--text); }
        .btn-danger-n { background: transparent; color: #c0392b; border: 1px solid #f5c6cb; border-radius: 6px; font-size: 13px; padding: 7px 16px; }
        .btn-danger-n:hover { background: #fff5f5; }

        /* Cards */
        .card-n { background: white; border: 1px solid var(--border); border-radius: 10px; }
        .stat-card { background: white; border: 1px solid var(--border); border-radius: 10px; padding: 20px; }
        .stat-val { font-size: 26px; font-weight: 500; color: var(--text); line-height: 1; }
        .stat-label { font-size: 11px; color: var(--muted); text-transform: uppercase; letter-spacing: 0.8px; margin-top: 4px; }

        /* Entry card */
        .entry-item { padding: 20px 0; border-bottom: 1px solid var(--border); display: flex; gap: 20px; align-items: flex-start; }
        .entry-item:last-child { border-bottom: none; }
        .entry-thumb { width: 80px; height: 80px; border-radius: 8px; object-fit: cover; flex-shrink: 0; }
        .entry-title { font-size: 16px; font-weight: 500; color: var(--text); text-decoration: none; line-height: 1.4; }
        .entry-title:hover { color: var(--accent); }
        .entry-preview { font-size: 13px; color: var(--muted); line-height: 1.6; margin-top: 4px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

        /* Mood */
        .mood-dot { width: 7px; height: 7px; border-radius: 50%; display: inline-block; flex-shrink: 0; }
        .mood-label { font-size: 11px; color: var(--muted); }

        /* Tags */
        .tag-pill { font-size: 11px; padding: 3px 10px; border-radius: 20px; background: #F4F4F4; color: #666; text-decoration: none; display: inline-block; }
        .tag-pill:hover { background: var(--accent-light); color: var(--accent); }

        /* Form */
        .form-control:focus, .form-select:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(92,91,229,0.1); }

        /* Reading content */
        .prose { font-family: 'Lora', serif; font-size: 18px; line-height: 1.85; color: #2a2a2a; }
        .prose p { margin-bottom: 1.5em; }
        .prose img { max-width: 100%; border-radius: 8px; margin: 20px 0; display: block; }
        .prose blockquote { border-left: 3px solid var(--accent); padding-left: 20px; color: #555; font-style: italic; margin: 1.5em 0; }
        .prose h2 { font-size: 22px; font-weight: 500; margin: 1.5em 0 0.5em; }
        .prose h3 { font-size: 18px; font-weight: 500; margin: 1.2em 0 0.4em; }
        .prose ul, .prose ol { padding-left: 24px; margin-bottom: 1.4em; }

        /* Alert */
        .alert-success-n { background: #F0FDF6; color: #166534; border: 1px solid #BBF7D0; border-radius: 8px; padding: 12px 16px; font-size: 14px; }
        .alert-error-n { background: #FFF5F5; color: #c0392b; border: 1px solid #f5c6cb; border-radius: 8px; padding: 12px 16px; font-size: 14px; }

        /* Divider */
        .section-label { font-size: 11px; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; font-weight: 500; }

        footer { border-top: 1px solid var(--border); padding: 28px 0; margin-top: 60px; }
        footer p { font-size: 13px; color: var(--muted); margin: 0; }

        @media (max-width: 576px) {
            .entry-thumb { width: 64px; height: 64px; }
            .prose { font-size: 16px; }
        }

        /* ===== Kalender ===== */
.nv-calendar {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 4px;
}

.nv-cal-header {
    text-align: center;
    font-size: 11px;
    font-weight: 500;
    color: #999;
    padding: 4px 0;
}

.nv-cal-cell {
    position: relative;
    aspect-ratio: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    cursor: default;
    transition: background 0.15s;
}

.nv-cal-cell:hover:not(.nv-cal-empty) {
    background: #f5f5f5;
}

.nv-cal-empty {
    background: transparent;
}

.nv-cal-day-num {
    font-size: 12px;
    color: #333;
    line-height: 1;
}

.nv-cal-has-entry {
    background: #F5E6D3;
}

.nv-cal-has-entry .nv-cal-day-num {
    font-weight: 600;
    color: #6F4E37;
}

.nv-cal-today {
    border: 2px solid #5C5BE5;
}

.nv-cal-today .nv-cal-day-num {
    color: #5C5BE5;
    font-weight: 600;
}

.nv-cal-dot {
    position: absolute;
    bottom: 4px;
    left: 50%;
    transform: translateX(-50%);
    width: 4px;
    height: 4px;
    border-radius: 50%;
    background: #6F4E37;
    display: block;
}
    </style>

    @stack('styles')
</head>
<body>

<nav class="site-nav">
    <div class="container d-flex align-items-center justify-content-between">
        <a href="{{ route('home') }}" class="brand">no<span>t</span>ive</a>

        <div class="d-flex align-items-center gap-3">
        @auth
        <a href="{{ route('dashboard') }}" class="nav-link d-none d-md-inline">Dashboard</a>
        <a href="{{ route('entries.index') }}" class="nav-link d-none d-md-inline">Entri</a>
        {{-- About dihapus dari sini --}}
        <a href="{{ route('profile.edit') }}" class="nav-link d-none d-md-inline">Profil</a>
        <form method="POST" action="{{ route('logout') }}" class="m-0">
            @csrf
            <button type="submit" class="btn-outline-n">Keluar</button>
        </form>
        @else
            <a href="{{ route('about') }}" class="nav-link d-none d-md-inline">About</a>
            <a href="{{ route('login') }}" class="btn-outline-n">Masuk</a>
            <a href="{{ route('register') }}" class="btn-primary-n">Daftar</a>
        @endauth
        </div>
    </div>
</nav>

@if(session('success'))
<div class="container mt-3">
    <div class="alert-success-n d-flex align-items-center gap-2">
        <i class="ti ti-circle-check"></i> {{ session('success') }}
    </div>
</div>
@endif

@if(session('error'))
<div class="container mt-3">
    <div class="alert-error-n d-flex align-items-center gap-2">
        <i class="ti ti-alert-circle"></i> {{ session('error') }}
    </div>
</div>
@endif

<main>
    @yield('content')
</main>

<footer>
    <div class="container text-center">
        <p>notive &mdash; ruang untuk pikiran yang jujur.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>