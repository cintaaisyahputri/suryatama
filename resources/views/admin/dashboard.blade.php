@extends('layouts.dashboard')

@section('title', 'Dashboard Admin — Suryatama')
@section('page-title', 'Ringkasan Operasional')

@php
    $authUser = auth()->user();
    $userName = $authUser->name;
    $userEmail = $authUser->email;
    $userRole = 'Administrator';
    $initials = strtoupper(collect(explode(' ', trim($userName)))
        ->filter()
        ->map(fn ($w) => mb_substr($w, 0, 1))
        ->take(2)
        ->implode('')) ?: 'A';

    $latestOrders = \App\Models\Order::with('user')->latest()->take(6)->get();

    $countMenungguSurvei = \App\Models\Order::where('status', 'menunggu_survei')->count();
    $countBaruMingguIni = \App\Models\Order::where('created_at', '>=', now()->subWeek())->count();
    $countBerjalan = \App\Models\Order::whereIn('status', ['survei_terjadwal', 'pemasangan'])->count();

    $ordersAktifBulanIni = \App\Models\Order::where('status', 'aktif')
        ->whereMonth('updated_at', now()->month)
        ->whereYear('updated_at', now()->year)
        ->get();
    $countTerpasangBulanIni = $ordersAktifBulanIni->count();
    $totalKwpBulanIni = $ordersAktifBulanIni->sum(function ($o) {
        return (float) str_replace(',', '.', preg_replace('/[^0-9,.]/', '', $o->capacity ?? '0'));
    });

    $pendapatanBulanIni = \App\Models\Invoice::whereNotNull('paid_at')
        ->whereMonth('paid_at', now()->month)
        ->whereYear('paid_at', now()->year)
        ->sum('amount');

    $jadwalHariIni = \App\Models\Order::with('user')
        ->whereNotNull('scheduled_at')
        ->whereDate('scheduled_at', now()->toDateString())
        ->orderBy('scheduled_at')
        ->get();
@endphp

@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@section('content')

    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
        <div class="card rounded-2xl p-6">
            <p class="text-xs text-soft uppercase tracking-wide">Permintaan baru</p>
            <p class="font-display text-2xl font-700 mt-2">{{ $countMenungguSurvei }}</p>
            <p class="text-xs text-teal-brand mt-2 font-mono">+{{ $countBaruMingguIni }} minggu ini</p>
        </div>
        <div class="card rounded-2xl p-6">
            <p class="text-xs text-soft uppercase tracking-wide">Sedang berjalan</p>
            <p class="font-display text-2xl font-700 mt-2">{{ $countBerjalan }}</p>
            <p class="text-xs text-teal-brand mt-2 font-mono">survei &amp; instalasi</p>
        </div>
        <div class="card rounded-2xl p-6">
            <p class="text-xs text-soft uppercase tracking-wide">Terpasang bulan ini</p>
            <p class="font-display text-2xl font-700 mt-2">{{ $countTerpasangBulanIni }}</p>
            <p class="text-xs text-teal-brand mt-2 font-mono">≈ {{ rtrim(rtrim(number_format($totalKwpBulanIni, 1, ',', '.'), '0'), ',') }} kWp total</p>
        </div>
        <div class="card rounded-2xl p-6">
            <p class="text-xs text-soft uppercase tracking-wide">Pendapatan bulan ini</p>
            <p class="font-display text-2xl font-700 mt-2">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</p>
            <p class="text-xs text-teal-brand mt-2 font-mono">dari invoice yang sudah lunas</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="card rounded-2xl p-6 lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <p class="font-display font-600">Permintaan konsultasi terbaru</p>
                <a href="{{ route('admin.requests') }}" class="text-xs font-semibold text-soft hover:text-[var(--ink)]">Lihat semua</a>
            </div>
            @if($latestOrders->isEmpty())
                <p class="text-sm text-soft py-6 text-center">Belum ada permintaan masuk.</p>
            @else
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs text-soft uppercase border-b border-line">
                            <th class="pb-2 font-medium">Pelanggan</th>
                            <th class="pb-2 font-medium">Lokasi</th>
                            <th class="pb-2 font-medium">Kapasitas</th>
                            <th class="pb-2 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        @foreach($latestOrders as $order)
                            <tr>
                                <td class="py-3 font-medium">{{ $order->user->name ?? '—' }}</td>
                                <td class="py-3 text-soft">{{ $order->city ?? '—' }}</td>
                                <td class="py-3 font-mono text-xs">{{ $order->capacity ?? '—' }}</td>
                                <td class="py-3">
                                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                                        {{ $order->statusTone() === 'amber' ? 'bg-amber-brand/15 text-amber-brand' : ($order->statusTone() === 'teal' ? 'bg-teal-brand/10 text-teal-brand' : 'bg-[var(--ink)]/10 text-[var(--ink)]') }}">
                                        {{ $order->statusLabel() }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div class="card rounded-2xl p-6">
            <p class="font-display font-600 mb-4">Jadwal teknisi — hari ini</p>
            @if($jadwalHariIni->isEmpty())
                <p class="text-sm text-soft">Tidak ada kunjungan terjadwal hari ini.</p>
            @else
                <div class="space-y-4">
                    @foreach($jadwalHariIni as $order)
                        <div class="flex gap-3">
                            <p class="font-mono text-xs text-soft w-12 pt-0.5">{{ $order->scheduled_at->format('H.i') }}</p>
                            <div class="flex-1 pb-4 border-b border-line last:border-0 last:pb-0">
                                <p class="text-sm font-medium">{{ $order->statusLabel() }} — {{ $order->user->name ?? '—' }}</p>
                                <p class="text-xs text-soft mt-0.5">Teknisi: {{ $order->technician_name ?? 'belum ditentukan' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

@endsection