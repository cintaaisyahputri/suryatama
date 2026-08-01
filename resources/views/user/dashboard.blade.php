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

    try {
        $hasOrders = $authUser->orders()->exists();
    } catch (\Throwable $e) {
        $hasOrders = false;
    }
    $isNewCustomer = ! $hasOrders;

    $latestOrder = $hasOrders ? $authUser->orders()->latest()->first() : null;

    if ($latestOrder) {
        $orderSteps = ['menunggu_survei', 'survei_terjadwal', 'pemasangan', 'aktif'];
        $currentIndex = array_search($latestOrder->status, $orderSteps);
        $progressSteps = [
            ['label' => 'Konsultasi', 'done' => true],
            ['label' => 'Survei atap', 'done' => $currentIndex >= 1, 'current' => $currentIndex === 1],
            ['label' => 'Pemasangan', 'done' => $currentIndex >= 2, 'current' => $currentIndex === 2],
            ['label' => 'Aktivasi', 'done' => $currentIndex >= 3, 'current' => $currentIndex === 3],
        ];
    }

    $autoAddress = $authUser->address;
    $autoCity = $authUser->city;

    $latestInvoices = \App\Models\Invoice::whereHas('order', fn ($q) => $q->where('user_id', $authUser->id))
        ->latest()
        ->take(2)
        ->get();
    /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\Invoice> $latestInvoices */
@endphp

@section('sidebar')
    @include('partials.sidebar-user')
@endsection

@section('content')

    @if($isNewCustomer)
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
        <div data-tutorial="buat-permintaan" class="card rounded-2xl p-7 mb-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-xs uppercase tracking-wide text-soft">Pesanan #SRY-{{ 2000 + $latestOrder->id }}</p>
                    <h2 class="font-display text-xl font-700 mt-1">{{ $latestOrder->capacity ?? '—' }} — {{ $latestOrder->city ?? 'Kota belum diisi' }}</h2>
                </div>
                <span class="text-xs font-semibold bg-teal-brand/10 text-teal-brand px-3 py-1.5 rounded-full">{{ $latestOrder->statusLabel() }}</span>
            </div>

            <div class="grid grid-cols-4 gap-2">
                @foreach($progressSteps as $s)
                    <div>
                        <div class="h-1.5 rounded-full {{ $s['done'] ? 'bg-teal-brand' : ($s['current'] ?? false ? 'bg-amber-brand' : 'bg-[var(--line)]') }}"></div>
                        <p class="text-xs mt-2 font-medium {{ $s['done'] || ($s['current'] ?? false) ? 'text-[var(--ink)]' : 'text-soft' }}">{{ $s['label'] }}</p>
                    </div>
                @endforeach
            </div>

            @if($latestOrder->scheduled_at)
                <p class="text-sm text-soft mt-5">Teknisi dijadwalkan datang <span class="font-medium text-[var(--ink)]">{{ $latestOrder->scheduled_at->translatedFormat('l, d F Y · H:i') }}</span>{{ $latestOrder->technician_name ? ' oleh '.$latestOrder->technician_name : '' }}.</p>
            @else
                <p class="text-sm text-soft mt-5">Jadwal kunjungan teknisi belum ditentukan admin.</p>
            @endif
        </div>

        <details class="card rounded-2xl mb-6 group">
            <summary class="cursor-pointer list-none p-5 flex items-center justify-between font-display font-600">
                <span>+ Ajukan pemasangan baru</span>
                <span class="text-xs text-soft font-normal font-sans">Punya lokasi lain? Klik di sini</span>
            </summary>
            <div class="px-5 pb-6 pt-1 border-t border-line">
                <form method="POST" action="{{ route('orders.store') }}" class="mt-4 grid sm:grid-cols-2 gap-4 text-left max-w-md">
                    @csrf
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-medium mb-1.5">Alamat</label>
                        <input type="text" name="address" placeholder="Contoh: Jl. Merdeka No. 10, Depok"
                               class="w-full rounded-lg border border-line px-4 py-2.5 text-sm bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-medium mb-1.5">Kota</label>
                        <input type="text" name="city" class="w-full rounded-lg border border-line px-4 py-2.5 text-sm bg-white">
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
        </details>
    @endif

    <div class="grid lg:grid-cols-3 gap-6">
        <div data-tutorial="produksi" class="card rounded-2xl p-7 lg:col-span-2">
            <p class="text-xs uppercase tracking-wide text-soft mb-1">Produksi listrik hari ini</p>
            <p class="font-display text-2xl font-700 mb-6">— (aktif setelah aktivasi)</p>
            <svg viewBox="0 0 500 140" class="w-full h-auto opacity-40">
                <path d="M10 120 Q 250 15 490 120" fill="none" stroke="var(--amber)" stroke-width="3" stroke-linecap="round" stroke-dasharray="6 6"/>
            </svg>
            <p class="text-xs text-soft mt-3">Grafik akan menampilkan data real-time begitu sistem selesai diaktivasi.</p>
        </div>

        <div data-tutorial="invoice" class="card rounded-2xl p-7">
            <p class="text-xs uppercase tracking-wide text-soft mb-4">Invoice terbaru</p>

            @if($latestInvoices->isEmpty())
                <p class="text-sm text-soft">Belum ada invoice untuk pesanan kamu.</p>
            @else
                <div class="space-y-4 font-mono text-sm">
                    @foreach($latestInvoices as $invoice)
                        <div class="flex justify-between items-center pb-4 border-b border-line last:border-0 last:pb-0">
                            <div>
                                <p class="text-[var(--ink)]">{{ $invoice->label }}</p>
                                <p class="text-soft text-xs">
                                    {{ $invoice->isPaid() ? optional($invoice->paid_at)->translatedFormat('d M Y') : 'Jatuh tempo '.optional($invoice->due_date)->translatedFormat('d M Y') }}
                                </p>
                            </div>
                            <span class="text-xs font-semibold
                                {{ $invoice->statusTone() === 'teal' ? 'text-teal-brand' : ($invoice->statusTone() === 'red' ? 'text-red-600' : 'text-amber-brand') }}">
                                {{ $invoice->statusLabel() }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif

            <a href="{{ route('user.invoices') }}" class="block w-full mt-6 text-center border border-line rounded-lg py-2.5 text-sm font-semibold hover:bg-black/[.02]">
                Lihat semua invoice
            </a>
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