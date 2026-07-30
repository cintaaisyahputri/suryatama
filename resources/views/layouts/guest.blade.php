<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Suryatama')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@500&display=swap" rel="stylesheet">

    <style>
        :root{
            --bg: #F5F6F1; --ink: #16202B; --ink-soft: #4B5A67;
            --amber: #F0A202; --amber-deep: #C97F00; --teal: #0B6E4F; --line: #DCD9CE; --card: #FFFFFF;
        }
        body{ background: var(--bg); color: var(--ink); font-family: 'Inter', sans-serif; }
        .font-display{ font-family: 'Space Grotesk', sans-serif; }
        .font-mono{ font-family: 'IBM Plex Mono', monospace; }
        .text-soft{ color: var(--ink-soft); }
        .text-amber-brand{ color: var(--amber-deep); }
        .border-line{ border-color: var(--line); }
        .card{ background: var(--card); border: 1px solid var(--line); }
        .btn-primary{ background: var(--ink); color: #fff; }
        .btn-primary:hover{ background: var(--amber-deep); }
        input:focus{ outline: 2px solid var(--amber); outline-offset: 1px; }
    </style>
</head>
<body class="antialiased">
    <div class="min-h-screen grid lg:grid-cols-2">

        <div class="hidden lg:flex flex-col justify-between bg-[var(--ink)] text-white px-14 py-12">
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <svg width="26" height="26" viewBox="0 0 30 30" fill="none">
                    <path d="M15 2 L15 8" stroke="#F0A202" stroke-width="2.5" stroke-linecap="round"/>
                    <path d="M15 22 L15 28" stroke="#F0A202" stroke-width="2.5" stroke-linecap="round"/>
                    <path d="M4 15 L2 15" stroke="#F0A202" stroke-width="2.5" stroke-linecap="round"/>
                    <circle cx="15" cy="15" r="6.5" fill="#F0A202"/>
                </svg>
                <span class="font-display font-700 text-lg">Suryatama</span>
            </a>

            <div>
                <svg viewBox="0 0 420 160" class="w-full h-auto mb-8">
                    <path d="M10 140 Q 210 5 410 140" fill="none" stroke="rgba(255,255,255,.15)" stroke-width="2"/>
                    <path d="M10 140 Q 210 45 410 140" fill="none" stroke="#F0A202" stroke-width="3" stroke-linecap="round"/>
                    <circle cx="210" cy="45" r="7" fill="#F0A202"/>
                </svg>
                <h2 class="font-display text-2xl font-700 leading-snug max-w-sm">Setiap akun terhubung ke satu titik atap yang sedang bekerja.</h2>
                <p class="text-white/50 text-sm mt-4 max-w-sm">Pantau jadwal survei, status pemasangan, dan produksi listrik dari satu tempat.</p>
            </div>

            <p class="font-mono text-xs text-white/40">© {{ date('Y') }} Suryatama</p>
        </div>

        <div class="flex items-center justify-center px-6 py-16">
            <div class="w-full max-w-sm">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>