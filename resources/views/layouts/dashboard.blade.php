<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard — Suryatama')</title>

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
        .text-teal-brand{ color: var(--teal); }
        .bg-teal-brand{ background: var(--teal); }
        .border-line{ border-color: var(--line); }
        .card{ background: var(--card); border: 1px solid var(--line); }
        .nav-link{ color: var(--ink-soft); }
        .nav-link:hover{ background: rgba(0,0,0,.03); color: var(--ink); }
        .nav-link.active{ background: var(--ink); color: #fff; }
    </style>
</head>
<body class="antialiased">
<div class="min-h-screen flex">

    <aside class="hidden lg:flex flex-col w-64 shrink-0 border-r border-line bg-white px-5 py-6">
        <a href="{{ url('/') }}" class="flex items-center gap-2 px-2 mb-8">
            <svg width="24" height="24" viewBox="0 0 30 30" fill="none">
                <path d="M15 2 L15 8" stroke="#F0A202" stroke-width="2.5" stroke-linecap="round"/>
                <path d="M15 22 L15 28" stroke="#F0A202" stroke-width="2.5" stroke-linecap="round"/>
                <path d="M4 15 L2 15" stroke="#F0A202" stroke-width="2.5" stroke-linecap="round"/>
                <circle cx="15" cy="15" r="6.5" fill="#F0A202"/>
            </svg>
            <span class="font-display font-700">Suryatama</span>
        </a>

        <nav class="flex-1 space-y-1">
            @yield('sidebar')
        </nav>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium mt-2">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                Keluar
            </button>
        </form>
    </aside>

    <div class="flex-1 min-w-0">
        
        <header class="h-16 border-b border-line bg-white/80 backdrop-blur flex items-center justify-between px-6">
            <h1 class="font-display font-600 text-lg">@yield('page-title', 'Dashboard')</h1>
            <div class="flex items-center gap-4">
                <x-notifications />
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-[var(--ink)] text-white flex items-center justify-center text-xs font-semibold">
                        {{ $initials ?? 'SU' }}
                    </div>
                    <div class="hidden sm:block leading-tight">
                        <p class="text-sm font-medium">{{ $userName ?? 'Pengguna' }}</p>
                        <p class="text-xs text-soft">{{ $userEmail ?? ($userRole ?? '') }}</p>
                    </div>
                </div>
            </div>
        </header>

        <main class="p-6">
            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
