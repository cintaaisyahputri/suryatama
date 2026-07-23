@extends('layouts.dashboard')

@section('title', 'Permintaan Konsultasi — Suryatama')
@section('page-title', 'Permintaan Konsultasi')

@php
    $authUser = auth()->user();
    $userName = $authUser->name;
    $userEmail = $authUser->email;
    $userRole = 'Administrator';
    $initials = strtoupper(collect(explode(' ', trim($userName)))
        ->filter()->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->implode('')) ?: 'A';

    $orders = \App\Models\Order::with('user')->latest()->get();
    $statusOptions = [
        'menunggu_survei' => 'Menunggu survei',
        'survei_terjadwal' => 'Survei terjadwal',
        'pemasangan' => 'Pemasangan',
        'aktif' => 'Aktif',
    ];
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
            <h2 class="font-display text-xl font-700">Belum ada permintaan masuk</h2>
            <p class="text-soft text-sm mt-2">Permintaan survei dari pelanggan akan muncul di sini.</p>
        </div>
    @else
        <div class="card rounded-2xl overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-soft uppercase border-b border-line bg-black/[.015]">
                        <th class="p-4 font-medium">Pelanggan</th>
                        <th class="p-4 font-medium">Lokasi</th>
                        <th class="p-4 font-medium">Kapasitas</th>
                        <th class="p-4 font-medium">Diajukan</th>
                        <th class="p-4 font-medium">Status</th>
                        <th class="p-4 font-medium"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @foreach($orders as $order)
                        <tr>
                            <td class="p-4">
                                <p class="font-medium">{{ $order->user->name ?? '—' }}</p>
                                <p class="text-xs text-soft">{{ $order->user->email ?? '' }}</p>
                            </td>
                            <td class="p-4 text-soft">{{ $order->city ?? '—' }}</td>
                            <td class="p-4 font-mono text-xs">{{ $order->capacity ?? '—' }}</td>
                            <td class="p-4 text-soft text-xs">{{ $order->created_at->translatedFormat('d M Y') }}</td>
                            <td class="p-4">
                                <form method="POST" action="{{ route('admin.requests.update', $order) }}">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" onchange="this.form.submit()"
                                            class="text-xs font-semibold rounded-full px-3 py-1.5 border border-line bg-white">
                                        @foreach($statusOptions as $value => $label)
                                            <option value="{{ $value }}" @selected($order->status === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td class="p-4 text-right">
                                <a href="{{ route('admin.schedule') }}" class="text-xs font-semibold text-soft hover:text-[var(--ink)]">Atur jadwal →</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

@endsection
