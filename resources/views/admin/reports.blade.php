@extends('layouts.dashboard')

@section('title', 'Laporan & Invoice — Suryatama')
@section('page-title', 'Laporan & Invoice')

@php
    $authUser = auth()->user();
    $userName = $authUser->name;
    $userEmail = $authUser->email;
    $userRole = 'Administrator';
    $initials = strtoupper(collect(explode(' ', trim($userName)))
        ->filter()->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->implode('')) ?: 'A';

    $invoices = \App\Models\Invoice::with('order.user')->latest()->get();

    $totalLunas = $invoices->filter->isPaid()->sum('amount');
    $totalMenunggu = $invoices->reject->isPaid()->sum('amount');
@endphp

@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@section('content')

    <div class="grid sm:grid-cols-2 gap-5 mb-6">
        <div class="card rounded-2xl p-6">
            <p class="text-xs text-soft uppercase tracking-wide">Total sudah dibayar</p>
            <p class="font-display text-2xl font-700 mt-2 text-teal-brand">Rp {{ number_format($totalLunas, 0, ',', '.') }}</p>
        </div>
        <div class="card rounded-2xl p-6">
            <p class="text-xs text-soft uppercase tracking-wide">Total belum dibayar</p>
            <p class="font-display text-2xl font-700 mt-2 text-amber-brand">Rp {{ number_format($totalMenunggu, 0, ',', '.') }}</p>
        </div>
    </div>

    @if($invoices->isEmpty())
        <div class="card rounded-2xl p-8 text-center">
            <h2 class="font-display text-xl font-700">Belum ada invoice</h2>
            <p class="text-soft text-sm mt-2">Invoice akan muncul di sini setelah dibuat untuk sebuah pesanan.</p>
        </div>
    @else
        <div class="card rounded-2xl overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-soft uppercase border-b border-line bg-black/[.015]">
                        <th class="p-4 font-medium">Pelanggan</th>
                        <th class="p-4 font-medium">Label</th>
                        <th class="p-4 font-medium">Jumlah</th>
                        <th class="p-4 font-medium">Jatuh tempo</th>
                        <th class="p-4 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @foreach($invoices as $invoice)
                        <tr>
                            <td class="p-4 font-medium">{{ $invoice->order->user->name ?? '—' }}</td>
                            <td class="p-4">{{ $invoice->label }}</td>
                            <td class="p-4 font-mono">{{ $invoice->amountFormatted() }}</td>
                            <td class="p-4 text-soft">{{ $invoice->due_date?->translatedFormat('d M Y') ?? '—' }}</td>
                            <td class="p-4">
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                                    {{ $invoice->statusTone() === 'teal' ? 'bg-teal-brand/10 text-teal-brand' : ($invoice->statusTone() === 'red' ? 'bg-red-500/10 text-red-600' : 'bg-amber-brand/15 text-amber-brand') }}">
                                    {{ $invoice->statusLabel() }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

@endsection
