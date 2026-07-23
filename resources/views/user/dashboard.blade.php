@extends('layouts.dashboard')

@section('title', 'Dashboard Saya — Suryatama')
@section('page-title', 'Dashboard Saya')

@php
    $authUser = auth()->user();
    $userName = $authUser->name;
    $userEmail = $authUser->email;
    $userRole = 'Pelanggan';
    $initials = strtoupper(collect(explode(' ', trim($userName)))
        ->filter()
        ->map(fn ($w) => mb_substr($w, 0, 1))
        ->take(2)
        ->implode('')) ?: 'U';

    // Cek apakah pelanggan ini baru (belum pernah punya pesanan).
    // Dibungkus try/catch supaya tetap aman dipakai sebelum migration orders dijalankan.
    try {
        $hasOrders = $authUser->orders()->exists();
    } catch (\Throwable $e) {
        $hasOrders = false;
    }
    $isNewCustomer = ! $hasOrders;

    // Alamat otomatis dari deteksi IP saat login (diisi oleh SetUserLocationFromIp listener)
    $autoAddress = $authUser->address;
    $autoCity = $authUser->city;
@endphp

@section('sidebar')
    @include('partials.sidebar-user')
@endsection

@section('content')

    @if($isNewCustomer)
        {{-- Empty state: pelanggan baru, belum ada pesanan --}}
        <div data-tutorial="buat-permintaan" class="card rounded-2xl p-8 mb-6 text-center">
            <div class="w-12 h-12 mx-auto rounded-full bg-amber-brand/15 flex items-center justify-center mb-4">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--amber-deep)" stroke-width="2"><path d="M13 2 3 14h7l-1 8 10-12h-7l1-8z"/></svg>
            </div>
            <h2 class="font-display text-xl font-700">Belum ada permintaan pemasangan</h2>
            <p class="text-soft text-sm mt-2 max-w-sm mx-auto leading-relaxed">
                Mulai dengan mengajukan survei gratis. Lokasi sudah kami isi otomatis berdasarkan lokasimu saat masuk — silakan koreksi kalau kurang tepat.
            </p>

            <form method="POST" action="{{ route('orders.store') }}" class="mt-6 grid sm:grid-cols-2 gap-4 text-left max-w-md mx-auto">
                @csrf
                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium mb-1.5">Alamat <span class="text-soft font-normal">(otomatis dari lokasimu)</span></label>
                    <input type="text" name="address" value="{{ old('address', $autoAddress) }}"
                           placeholder="Contoh: Jl. Merdeka No. 10, Depok"
                           class="w-full rounded-lg border border-line px-4 py-2.5 text-sm bg-white">
                </div>
                <div>
                    <label class="block text-xs font-medium mb-1.5">Kota</label>
                    <input type="text" name="city" value="{{ old('city', $autoCity) }}"
                           class="w-full rounded-lg border border-line px-4 py-2.5 text-sm bg-white">
                </div>
                <div>
                    <label class="block text-xs font-medium mb-1.5">Kapasitas perkiraan</label>
                    <select name="capacity" class="w-full rounded-lg border border-line px-4 py-2.5 text-sm bg-white">
                        <option>2 kWp</option>
                        <option selected>3 kWp</option>
                        <option>5 kWp</option>
                        <option>10 kWp</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary sm:col-span-2 rounded-lg py-3 text-sm font-semibold transition-colors">
                    Ajukan survei gratis
                </button>
            </form>
        </div>
    @else
        {{-- Status pemasangan: pelanggan yang sudah punya pesanan berjalan --}}
        <div data-tutorial="buat-permintaan" class="card rounded-2xl p-7 mb-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-xs uppercase tracking-wide text-soft">Status pesanan #SRY-2291</p>
                    <h2 class="font-display text-xl font-700 mt-1">Sistem 3 kWp — Rumah, Depok</h2>
                </div>
                <span class="text-xs font-semibold bg-teal-brand/10 text-teal-brand px-3 py-1.5 rounded-full">Sedang berjalan</span>
            </div>

            <div class="grid grid-cols-4 gap-2">
                @foreach([
                    ['label' => 'Konsultasi', 'done' => true],
                    ['label' => 'Survei atap', 'done' => true],
                    ['label' => 'Pemasangan', 'done' => false, 'current' => true],
                    ['label' => 'Aktivasi', 'done' => false],
                ] as $s)
                    <div>
                        <div class="h-1.5 rounded-full {{ $s['done'] ? 'bg-teal-brand' : ($s['current'] ?? false ? 'bg-amber-brand' : 'bg-[var(--line)]') }}"></div>
                        <p class="text-xs mt-2 font-medium {{ $s['done'] || ($s['current'] ?? false) ? 'text-[var(--ink)]' : 'text-soft' }}">{{ $s['label'] }}</p>
                    </div>
                @endforeach
            </div>
            <p class="text-sm text-soft mt-5">Teknisi dijadwalkan datang <span class="font-medium text-[var(--ink)]">Kamis, 24 Juli 2026 · 09.00</span> untuk pemasangan panel.</p>
        </div>
    @endif

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Produksi listrik --}}
        <div data-tutorial="produksi" class="card rounded-2xl p-7 lg:col-span-2">
            <p class="text-xs uppercase tracking-wide text-soft mb-1">Produksi listrik hari ini</p>
            <p class="font-display text-2xl font-700 mb-6">— (aktif setelah aktivasi)</p>
            <svg viewBox="0 0 500 140" class="w-full h-auto opacity-40">
                <path d="M10 120 Q 250 15 490 120" fill="none" stroke="var(--amber)" stroke-width="3" stroke-linecap="round" stroke-dasharray="6 6"/>
            </svg>
            <p class="text-xs text-soft mt-3">Grafik akan menampilkan data real-time begitu sistem selesai diaktivasi.</p>
        </div>

        {{-- Invoice --}}
        <div data-tutorial="invoice" class="card rounded-2xl p-7">
            <p class="text-xs uppercase tracking-wide text-soft mb-4">Invoice terbaru</p>
            <div class="space-y-4 font-mono text-sm">
                <div class="flex justify-between items-center pb-4 border-b border-line">
                    <div>
                        <p class="text-[var(--ink)]">DP Pemasangan</p>
                        <p class="text-soft text-xs">10 Jul 2026</p>
                    </div>
                    <span class="text-teal-brand text-xs font-semibold">Lunas</span>
                </div>
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-[var(--ink)]">Pelunasan</p>
                        <p class="text-soft text-xs">Jatuh tempo 28 Jul 2026</p>
                    </div>
                    <span class="text-amber-brand text-xs font-semibold">Menunggu</span>
                </div>
            </div>
            <button class="w-full mt-6 border border-line rounded-lg py-2.5 text-sm font-semibold hover:bg-black/[.02]">Lihat semua invoice</button>
        </div>
    </div>

    @if($isNewCustomer)
        <x-tutorial storage-key="suryatama_tutorial_user" :steps="[
            ['target' => '[data-tutorial=\"buat-permintaan\"]', 'title' => 'Mulai di sini', 'text' => 'Ajukan survei gratis pertamamu — alamat sudah kami isi otomatis dari lokasimu.'],
            ['target' => '[data-tutorial=\"sidebar-jadwal\"]', 'title' => 'Jadwal Survei', 'text' => 'Setelah diajukan, jadwal kedatangan teknisi bisa dicek di menu ini.'],
            ['target' => '[data-tutorial=\"produksi\"]', 'title' => 'Produksi Listrik', 'text' => 'Begitu sistem aktif, produksi listrik harianmu tampil real-time di sini.'],
            ['target' => '[data-tutorial=\"invoice\"]', 'title' => 'Invoice', 'text' => 'Tagihan DP dan pelunasan juga bisa kamu pantau langsung dari sini.'],
        ]" />
    @endif

@endsection
