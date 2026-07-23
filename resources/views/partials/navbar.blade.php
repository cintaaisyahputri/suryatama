<header class="sticky top-0 z-40 bg-[var(--bg)]/90 backdrop-blur border-b border-line">
    <nav class="max-w-6xl mx-auto px-6 h-20 flex items-center justify-between">
        <a href="{{ url('/') }}" class="flex items-center gap-2">
            <svg width="30" height="30" viewBox="0 0 30 30" fill="none">
                <path d="M15 2 L15 8" stroke="#F0A202" stroke-width="2.5" stroke-linecap="round"/>
                <path d="M15 22 L15 28" stroke="#F0A202" stroke-width="2.5" stroke-linecap="round"/>
                <path d="M4 15 L2 15" stroke="#F0A202" stroke-width="2.5" stroke-linecap="round"/>
                <circle cx="15" cy="15" r="6.5" fill="#F0A202"/>
            </svg>
            <span class="font-display font-700 text-lg tracking-tight">Suryatama</span>
        </a>

        <div class="hidden md:flex items-center gap-8 text-sm font-medium text-soft">
            <a href="{{ url('/#layanan') }}" class="hover:text-[var(--ink)]">Layanan</a>
            <a href="{{ url('/#proses') }}" class="hover:text-[var(--ink)]">Proses</a>
            <a href="{{ url('/#testimoni') }}" class="hover:text-[var(--ink)]">Testimoni</a>
            <a href="{{ url('/#kontak') }}" class="hover:text-[var(--ink)]">Kontak</a>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('login') }}" class="hidden sm:inline text-sm font-medium text-soft hover:text-[var(--ink)]">Masuk</a>
            <a href="{{ route('register') }}" class="btn-primary text-sm font-semibold px-5 py-2.5 rounded-full transition-colors">Konsultasi Gratis</a>
        </div>
    </nav>
</header>
