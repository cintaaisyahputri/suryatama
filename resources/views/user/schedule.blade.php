@extends('layouts.dashboard')

@section('title', 'Jadwal Survei — Suryatama')
@section('page-title', 'Jadwal Survei')

@php
    $authUser = auth()->user();
    $userName = $authUser->name;
    $userEmail = $authUser->email;
    $userRole = 'Pelanggan';
    $initials = strtoupper(collect(explode(' ', trim($userName)))
        ->filter()->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->implode('')) ?: 'U';

    $orders = $authUser->orders()->latest()->get();
@endphp

@section('sidebar')
    @include('partials.sidebar-user')
@endsection

@section('content')

    @if($orders->isEmpty())
        <div class="card rounded-2xl p-8 text-center">
            <div class="w-12 h-12 mx-auto rounded-full bg-amber-brand/15 flex items-center justify-center mb-4">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--amber-deep)" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
            </div>
            <h2 class="font-display text-xl font-700">Belum ada jadwal survei</h2>
            <p class="text-soft text-sm mt-2 max-w-sm mx-auto">Ajukan permintaan survei dulu dari halaman Ringkasan, nanti jadwal kunjungan teknisi akan muncul di sini.</p>
            <a href="{{ route('user.dashboard') }}" class="btn-primary inline-block mt-6 px-6 py-3 rounded-full text-sm font-semibold">Ke halaman Ringkasan</a>
        </div>
    @else
        <div class="space-y-5">
            @foreach($orders as $order)
                <div class="card rounded-2xl p-6">
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-soft">Pesanan #SRY-{{ 2000 + $order->id }}</p>
                            <h3 class="font-display font-600 text-lg mt-1">{{ $order->capacity ?? '—' }} — {{ $order->city ?? 'Belum diisi' }}</h3>
                        </div>
                        <span class="text-xs font-semibold px-3 py-1.5 rounded-full
                            {{ $order->statusTone() === 'amber' ? 'bg-amber-brand/15 text-amber-brand' : ($order->statusTone() === 'teal' ? 'bg-teal-brand/10 text-teal-brand' : 'bg-[var(--ink)]/10 text-[var(--ink)]') }}">
                            {{ $order->statusLabel() }}
                        </span>
                    </div>

                    <div class="grid sm:grid-cols-3 gap-4 font-mono text-sm">
                        <div>
                            <p class="text-[11px] text-soft uppercase">Jadwal kunjungan</p>
                            <p class="mt-1">{{ $order->scheduled_at ? $order->scheduled_at->translatedFormat('d M Y · H:i') : 'Belum dijadwalkan' }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] text-soft uppercase">Teknisi</p>
                            <p class="mt-1">{{ $order->technician_name ?? 'Belum ditentukan' }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] text-soft uppercase">Alamat</p>
                            <p class="mt-1">{{ $order->address ?? '—' }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

@endsection
