@extends('layouts.dashboard')

@section('title', 'Produksi Listrik — Suryatama')
@section('page-title', 'Produksi Listrik')

@php
    $authUser = auth()->user();
    $userName = $authUser->name;
    $userEmail = $authUser->email;
    $userRole = 'Pelanggan';
    $initials = strtoupper(collect(explode(' ', trim($userName)))
        ->filter()->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->implode('')) ?: 'U';

    $activeOrder = $authUser->orders()->where('status', 'aktif')->latest()->first();
@endphp

@section('sidebar')
    @include('partials.sidebar-user')
@endsection

@section('content')

    @if(! $activeOrder)
        <div class="card rounded-2xl p-8 text-center">
            <div class="w-12 h-12 mx-auto rounded-full bg-amber-brand/15 flex items-center justify-center mb-4">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--amber-deep)" stroke-width="2"><path d="M13 2 3 14h7l-1 8 10-12h-7l1-8z"/></svg>
            </div>
            <h2 class="font-display text-xl font-700">Sistem belum aktif</h2>
            <p class="text-soft text-sm mt-2 max-w-sm mx-auto">Data produksi listrik akan tampil di sini secara real-time begitu proses pemasangan selesai dan sistem diaktivasi teknisi kami.</p>
            <svg viewBox="0 0 500 140" class="w-full max-w-md mx-auto h-auto opacity-30 mt-6">
                <path d="M10 120 Q 250 15 490 120" fill="none" stroke="var(--amber)" stroke-width="3" stroke-linecap="round" stroke-dasharray="6 6"/>
            </svg>
        </div>
    @else
        <div class="grid lg:grid-cols-3 gap-6">
            <div class="card rounded-2xl p-7 lg:col-span-2">
                <p class="text-xs uppercase tracking-wide text-soft mb-1">Produksi hari ini</p>
                <p class="font-display text-2xl font-700 mb-6">14,6 kWh</p>
                <svg viewBox="0 0 500 140" class="w-full h-auto">
                    <path d="M10 120 Q 250 15 490 120" fill="none" stroke="var(--amber)" stroke-width="3" stroke-linecap="round"/>
                </svg>
                <p class="text-xs text-soft mt-3">Puncak produksi biasanya terjadi sekitar pukul 12.00–13.00.</p>
            </div>

            <div class="space-y-4">
                <div class="card rounded-2xl p-6">
                    <p class="text-xs uppercase tracking-wide text-soft">Estimasi hemat / bulan</p>
                    <p class="font-display text-xl font-700 mt-1 text-teal-brand">Rp 612.000</p>
                </div>
                <div class="card rounded-2xl p-6">
                    <p class="text-xs uppercase tracking-wide text-soft">Total produksi bulan ini</p>
                    <p class="font-display text-xl font-700 mt-1">402 kWh</p>
                </div>
                <div class="card rounded-2xl p-6">
                    <p class="text-xs uppercase tracking-wide text-soft">CO₂ terkonversi / tahun</p>
                    <p class="font-display text-xl font-700 mt-1">6,2 ton</p>
                </div>
            </div>
        </div>
    @endif

@endsection
