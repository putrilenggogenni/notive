<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Notive') }} — @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #FAFAFA; color: #1A1A1A; }
        .brand { font-size: 20px; font-weight: 500; letter-spacing: -0.5px; color: #1A1A1A; text-decoration: none; }
        .brand span { color: #5C5BE5; }
        .form-control:focus { border-color: #5C5BE5; box-shadow: 0 0 0 3px rgba(92,91,229,0.1); }
        .btn-primary-n { background: #1A1A1A; color: white; border: none; border-radius: 6px; font-size: 14px; padding: 10px 20px; width: 100%; }
        .btn-primary-n:hover { background: #333; color: white; }
        .card-auth { background: white; border: 1px solid #E8E8E8; border-radius: 12px; padding: 36px; }
        label { font-size: 13px; color: #555; font-weight: 500; margin-bottom: 6px; }
        .form-control { border-radius: 8px; font-size: 14px; border: 1px solid #E8E8E8; padding: 10px 14px; }
    </style>
</head>
<body>
<div class="min-vh-100 d-flex flex-column justify-content-center py-5">
    <div class="text-center mb-4">
        <a href="{{ route('home') }}" class="brand">no<span>t</span>ive</a>
    </div>
    <div class="container" style="max-width: 420px;">
        @yield('content')
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>