@extends('layouts.app')

@section('title', 'Suryatama — Pemasangan & Konsultasi Panel Surya')

@section('content')

    {{-- HERO --}}
    <section class="max-w-6xl mx-auto px-6 pt-16 pb-20 grid lg:grid-cols-2 gap-12 items-center">
        <div>
            <p class="font-mono text-xs tracking-widest uppercase text-amber-brand mb-4">Survei · Desain · Instalasi</p>
            <h1 class="font-display text-4xl sm:text-5xl font-700 leading-[1.08] tracking-tight">
                Atap Anda menyimpan energi<br> yang belum dipakai.
            </h1>
            <p class="text-soft mt-6 text-lg leading-relaxed max-w-md">
                Kami hitung potensi listrik dari atap rumah atau usaha Anda, lalu pasangkan sistem PLTS yang sesuai — dengan estimasi biaya dan hasil yang transparan sejak konsultasi pertama.
            </p>
            <div class="mt-8 flex items-center gap-4">
                <a href="{{ route('register') }}" class="btn-primary px-6 py-3.5 rounded-full font-semibold text-sm transition-colors">Jadwalkan survei gratis</a>
                <a href="#proses" class="text-sm font-semibold text-soft hover:text-[var(--ink)]">Lihat prosesnya →</a>
            </div>
            <div class="mt-10 flex items-center gap-8 font-mono">
                <div>
                    <p class="text-2xl font-500">1.240+</p>
                    <p class="text-xs text-soft">atap terpasang</p>
                </div>
                <div class="w-px h-9 bg-[var(--line)]"></div>
                <div>
                    <p class="text-2xl font-500">4.8/5</p>
                    <p class="text-xs text-soft">rating pelanggan</p>
                </div>
                <div class="w-px h-9 bg-[var(--line)]"></div>
                <div>
                    <p class="text-2xl font-500">±4,2 th</p>
                    <p class="text-xs text-soft">rata-rata balik modal</p>
                </div>
            </div>
        </div>

        {{-- Signature element: sun-path arc showing production across the day --}}
        <div class="card rounded-3xl p-8">
            <p class="text-xs font-semibold uppercase tracking-wide text-soft mb-1">Simulasi produksi harian</p>
            <p class="font-display text-lg mb-6">Rumah 3 kWp · Jakarta Selatan</p>
            <svg viewBox="0 0 420 220" class="w-full h-auto">
                <path d="M20 190 Q 210 10 400 190" fill="none" stroke="var(--line)" stroke-width="2"/>
                <path d="M20 190 Q 210 55 400 190" fill="none" stroke="var(--amber)" stroke-width="3" stroke-linecap="round"/>
                <circle cx="20" cy="190" r="4" fill="var(--ink)"/>
                <circle cx="400" cy="190" r="4" fill="var(--ink)"/>
                <circle cx="210" cy="55" r="7" fill="var(--amber)"/>
                <text x="10" y="212" font-family="IBM Plex Mono" font-size="12" fill="#4B5A67">06.00</text>
                <text x="195" y="35" font-family="IBM Plex Mono" font-size="12" fill="#16202B">12.00 · puncak</text>
                <text x="365" y="212" font-family="IBM Plex Mono" font-size="12" fill="#4B5A67">18.00</text>
            </svg>
            <div class="grid grid-cols-3 gap-4 mt-4 pt-6 border-t border-line font-mono text-center">
                <div>
                    <p class="text-lg">14,6 kWh</p>
                    <p class="text-[11px] text-soft mt-1">produksi / hari</p>
                </div>
                <div>
                    <p class="text-lg text-teal-brand">Rp 612rb</p>
                    <p class="text-[11px] text-soft mt-1">hemat / bulan</p>
                </div>
                <div>
                    <p class="text-lg">6,2 ton</p>
                    <p class="text-[11px] text-soft mt-1">CO₂ / tahun</p>
                </div>
            </div>
        </div>
    </section>

    {{-- LAYANAN --}}
    <section id="layanan" class="max-w-6xl mx-auto px-6 py-20 border-t border-line">
        <p class="font-mono text-xs tracking-widest uppercase text-amber-brand mb-3">Layanan</p>
        <h2 class="font-display text-3xl font-700 max-w-lg">Satu tim, dari perhitungan hingga sistem menyala.</h2>

        <div class="grid md:grid-cols-3 gap-6 mt-12">
            <div class="card rounded-2xl p-7">
                <div class="w-10 h-10 rounded-full bg-amber-brand/15 flex items-center justify-center mb-5">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--amber-deep)" stroke-width="2"><path d="M3 12h18M12 3v18"/></svg>
                </div>
                <h3 class="font-display font-600 text-lg mb-2">Survei &amp; Simulasi</h3>
                <p class="text-soft text-sm leading-relaxed">Kunjungan teknisi untuk mengukur arah, kemiringan, dan bayangan atap, lalu simulasi produksi listrik yang realistis.</p>
            </div>
            <div class="card rounded-2xl p-7">
                <div class="w-10 h-10 rounded-full bg-amber-brand/15 flex items-center justify-center mb-5">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--amber-deep)" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>
                </div>
                <h3 class="font-display font-600 text-lg mb-2">Desain Sistem</h3>
                <p class="text-soft text-sm leading-relaxed">Penentuan kapasitas panel, inverter, dan jalur kabel yang paling efisien untuk kondisi bangunan Anda.</p>
            </div>
            <div class="card rounded-2xl p-7">
                <div class="w-10 h-10 rounded-full bg-amber-brand/15 flex items-center justify-center mb-5">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--amber-deep)" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                </div>
                <h3 class="font-display font-600 text-lg mb-2">Instalasi &amp; Perawatan</h3>
                <p class="text-soft text-sm leading-relaxed">Pemasangan oleh teknisi bersertifikat, aktivasi sistem, dan jadwal perawatan berkala sesudahnya.</p>
            </div>
        </div>
    </section>

    {{-- PROSES: numbered sequence — genuinely sequential --}}
    <section id="proses" class="max-w-6xl mx-auto px-6 py-20 border-t border-line">
        <p class="font-mono text-xs tracking-widest uppercase text-amber-brand mb-3">Proses</p>
        <h2 class="font-display text-3xl font-700 max-w-lg">Empat tahap sampai listrik dari atap menyala.</h2>

        <div class="grid md:grid-cols-4 gap-8 mt-12">
            @foreach([
                ['no' => '01', 'title' => 'Konsultasi', 'desc' => 'Diskusi kebutuhan listrik dan anggaran melalui akun Anda.'],
                ['no' => '02', 'title' => 'Survei atap', 'desc' => 'Teknisi datang mengukur lokasi dan menyusun simulasi.'],
                ['no' => '03', 'title' => 'Pemasangan', 'desc' => 'Instalasi panel, inverter, dan pengkabelan di lokasi.'],
                ['no' => '04', 'title' => 'Aktivasi', 'desc' => 'Sistem dinyalakan, diuji, dan dipantau lewat dashboard.'],
            ] as $step)
                <div>
                    <p class="font-mono text-3xl text-[var(--line)]">{{ $step['no'] }}</p>
                    <h3 class="font-display font-600 mt-2">{{ $step['title'] }}</h3>
                    <p class="text-soft text-sm mt-2 leading-relaxed">{{ $step['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- TESTIMONI --}}
    <section id="testimoni" class="max-w-6xl mx-auto px-6 py-20 border-t border-line">
        <p class="font-mono text-xs tracking-widest uppercase text-amber-brand mb-3">Testimoni</p>
        <h2 class="font-display text-3xl font-700 max-w-lg mb-12">Dipercaya pemilik rumah dan usaha kecil.</h2>

        <div class="grid md:grid-cols-3 gap-6">
            @foreach([
                ['name' => 'Dian R.', 'role' => 'Pemilik rumah, Depok', 'quote' => 'Tagihan listrik turun sejak bulan pertama, prosesnya juga dijelaskan step demi step.'],
                ['name' => 'Bagus S.', 'role' => 'Pemilik warung, Bandung', 'quote' => 'Timnya datang tepat waktu dan hasil simulasi sebelum pasang sesuai kenyataan.'],
                ['name' => 'Nadia F.', 'role' => 'Manajer toko, Surabaya', 'quote' => 'Dashboard pemantauannya membantu kami cek produksi tanpa perlu telepon teknisi.'],
            ] as $t)
                <div class="card rounded-2xl p-7">
                    <p class="text-sm leading-relaxed">&ldquo;{{ $t['quote'] }}&rdquo;</p>
                    <p class="font-display font-600 text-sm mt-5">{{ $t['name'] }}</p>
                    <p class="text-soft text-xs">{{ $t['role'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- CTA --}}
    <section class="max-w-6xl mx-auto px-6 pb-24">
        <div class="rounded-3xl bg-[var(--ink)] text-white px-10 py-14 flex flex-col md:flex-row items-center justify-between gap-8">
            <div>
                <h2 class="font-display text-2xl sm:text-3xl font-700">Mulai dengan survei gratis untuk atap Anda.</h2>
                <p class="text-white/60 mt-3 max-w-md">Tanpa biaya, tanpa komitmen — hanya simulasi produksi dan estimasi biaya yang jelas.</p>
            </div>
            <a href="{{ route('register') }}" class="bg-amber-brand text-[var(--ink)] font-semibold px-7 py-3.5 rounded-full whitespace-nowrap">Daftar sekarang</a>
        </div>
    </section>

@endsection
