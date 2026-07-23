@extends('layouts.dashboard')

@section('title', 'Jadwal Teknisi — Suryatama')
@section('page-title', 'Jadwal Teknisi')

@php
    $authUser = auth()->user();
    $userName = $authUser->name;
    $userEmail = $authUser->email;
    $userRole = 'Administrator';
    $initials = strtoupper(collect(explode(' ', trim($userName)))
        ->filter()->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->implode('')) ?: 'A';

    $orders = \App\Models\Order::with('user')->latest()->get();
@endphp

@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@section('content')

    @if(session('status'))
        <div class="mb-6 rounded-lg bg-teal-brand/10 text-teal-brand text-sm px-4 py-3">{{ session('status') }}</div>
    @endif

    @if($orders->isEmpty())
        <div class="card rounded-2xl p-8 text-center">
            <h2 class="font-display text-xl font-700">Belum ada yang perlu dijadwalkan</h2>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="card rounded-2xl p-6">
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-soft">#SRY-{{ 2000 + $order->id }} · {{ $order->user->name ?? '—' }}</p>
                            <h3 class="font-display font-600 mt-1">{{ $order->capacity ?? '—' }} — {{ $order->city ?? '—' }}</h3>
                        </div>
                        <span class="text-xs font-semibold px-3 py-1.5 rounded-full
                            {{ $order->statusTone() === 'amber' ? 'bg-amber-brand/15 text-amber-brand' : ($order->statusTone() === 'teal' ? 'bg-teal-brand/10 text-teal-brand' : 'bg-[var(--ink)]/10 text-[var(--ink)]') }}">
                            {{ $order->statusLabel() }}
                        </span>
                    </div>

                    <form method="POST" action="{{ route('admin.schedule.update', $order) }}" class="grid sm:grid-cols-3 gap-4 items-end">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-xs font-medium mb-1.5">Tanggal &amp; jam kunjungan</label>
                            <input type="datetime-local" name="scheduled_at"
                                   value="{{ $order->scheduled_at ? $order->scheduled_at->format('Y-m-d\TH:i') : '' }}"
                                   class="w-full rounded-lg border border-line px-4 py-2.5 text-sm bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-medium mb-1.5">Nama teknisi</label>
                            <input type="text" name="technician_name" value="{{ $order->technician_name }}" placeholder="Contoh: Bagas"
                                   class="w-full rounded-lg border border-line px-4 py-2.5 text-sm bg-white">
                        </div>
                        <button type="submit" class="btn-primary rounded-lg py-2.5 text-sm font-semibold transition-colors">
                            Simpan jadwal
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif

@endsection
