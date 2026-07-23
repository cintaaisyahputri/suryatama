<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Suryatama — Pemasangan & Konsultasi Panel Surya')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@500&display=swap" rel="stylesheet">

    <style>
        :root{
            --bg: #F5F6F1;
            --ink: #16202B;
            --ink-soft: #4B5A67;
            --amber: #F0A202;
            --amber-deep: #C97F00;
            --teal: #0B6E4F;
            --line: #DCD9CE;
            --card: #FFFFFF;
        }
        body{ background: var(--bg); color: var(--ink); font-family: 'Inter', sans-serif; }
        .font-display{ font-family: 'Space Grotesk', sans-serif; }
        .font-mono{ font-family: 'IBM Plex Mono', monospace; }
        .text-soft{ color: var(--ink-soft); }
        .bg-amber-brand{ background: var(--amber); }
        .text-amber-brand{ color: var(--amber-deep); }
        .bg-teal-brand{ background: var(--teal); }
        .text-teal-brand{ color: var(--teal); }
        .border-line{ border-color: var(--line); }
        .card{ background: var(--card); border: 1px solid var(--line); }
        .btn-primary{ background: var(--ink); color: #fff; }
        .btn-primary:hover{ background: var(--amber-deep); }
    </style>
    @stack('styles')
</head>
<body class="antialiased">

    @include('partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    @stack('scripts')
</body>
</html>
