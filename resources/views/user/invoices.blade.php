@extends('layouts.dashboard')

@section('title', 'Invoice — Suryatama')
@section('page-title', 'Invoice')

@php
    $authUser = auth()->user();
    $userName = $authUser->name;
    $userEmail = $authUser->email;
    $userRole = 'Pelanggan';
    $initials = strtoupper(collect(explode(' ', trim($userName)))
        ->filter()->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->implode('')) ?: 'U';

    $invoices = \App\Models\Invoice::whereHas('order', fn ($q) => $q->where('user_id', $authUser->id))
        ->latest()
        ->get();
@endphp

@section('sidebar')
    @include('partials.sidebar-user')
@endsection

@section('content')

    @if($invoices->isEmpty())
        <div class="card rounded-2xl p-8 text-center">
            <div class="w-12 h-12 mx-auto rounded-full bg-amber-brand/15 flex items-center justify-center mb-4">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--amber-deep)" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
            </div>
            <h2 class="font-display text-xl font-700">Belum ada invoice</h2>
            <p class="text-soft text-sm mt-2 max-w-sm mx-auto">Invoice DP dan pelunasan akan muncul di sini setelah tim kami memproses permintaan pemasanganmu.</p>
        </div>
    @else
        <div class="card rounded-2xl overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-soft uppercase border-b border-line bg-black/[.015]">
                        <th class="p-4 font-medium">Label</th>
                        <th class="p-4 font-medium">Jumlah</th>
                        <th class="p-4 font-medium">Jatuh tempo</th>
                        <th class="p-4 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @foreach($invoices as $invoice)
                        <tr>
                            <td class="p-4 font-medium">{{ $invoice->label }}</td>
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
