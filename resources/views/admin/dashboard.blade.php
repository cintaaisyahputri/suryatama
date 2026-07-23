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

    // Ambil permintaan konsultasi terbaru dari tabel orders (diisi user lewat dashboard mereka).
    // Dibungkus try/catch supaya tetap aman dipakai sebelum migration dijalankan.
    try {
        $latestOrders = \App\Models\Order::with('user')->latest()->take(6)->get()->map(function ($o) {
            return [
                'name' => $o->user->name ?? '—',
                'loc' => $o->city ?? '—',
                'kwp' => $o->capacity ?? '—',
                'status' => $o->statusLabel(),
                'tone' => $o->statusTone(),
            ];
        })->all();
    } catch (\Throwable $e) {
        $latestOrders = [];
    }

    // Fallback data contoh selama tabel orders belum ada/masih kosong.
    if (empty($latestOrders)) {
        $latestOrders = [
            ['name' => 'Rahmat Hidayat', 'loc' => 'Bekasi', 'kwp' => '4 kWp', 'status' => 'Menunggu survei', 'tone' => 'amber'],
            ['name' => 'Siti Aminah', 'loc' => 'Tangerang', 'kwp' => '2 kWp', 'status' => 'Survei terjadwal', 'tone' => 'teal'],
            ['name' => 'CV Berkah Jaya', 'loc' => 'Bandung', 'kwp' => '10 kWp', 'status' => 'Menunggu survei', 'tone' => 'amber'],
            ['name' => 'Yusuf Pratama', 'loc' => 'Depok', 'kwp' => '3 kWp', 'status' => 'Pemasangan', 'tone' => 'ink'],
        ];
    }
@endphp

@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@section('content')

    {{-- Stat cards --}}
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
        @foreach([
            ['label' => 'Permintaan baru', 'value' => '18', 'note' => '+5 minggu ini'],
            ['label' => 'Sedang berjalan', 'value' => '32', 'note' => 'survei & instalasi'],
            ['label' => 'Terpasang bulan ini', 'value' => '11', 'note' => '≈ 33 kWp total'],
            ['label' => 'Pendapatan bulan ini', 'value' => 'Rp 412jt', 'note' => '+12% dari bulan lalu'],
        ] as $card)
            <div class="card rounded-2xl p-6">
                <p class="text-xs text-soft uppercase tracking-wide">{{ $card['label'] }}</p>
                <p class="font-display text-2xl font-700 mt-2">{{ $card['value'] }}</p>
                <p class="text-xs text-teal-brand mt-2 font-mono">{{ $card['note'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Permintaan konsultasi terbaru --}}
        <div class="card rounded-2xl p-6 lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <p class="font-display font-600">Permintaan konsultasi terbaru</p>
                <a href="#" class="text-xs font-semibold text-soft hover:text-[var(--ink)]">Lihat semua</a>
            </div>
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
                    @foreach($latestOrders as $row)
                        <tr>
                            <td class="py-3 font-medium">{{ $row['name'] }}</td>
                            <td class="py-3 text-soft">{{ $row['loc'] }}</td>
                            <td class="py-3 font-mono text-xs">{{ $row['kwp'] }}</td>
                            <td class="py-3">
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                                    {{ $row['tone'] === 'amber' ? 'bg-amber-brand/15 text-amber-brand' : ($row['tone'] === 'teal' ? 'bg-teal-brand/10 text-teal-brand' : 'bg-[var(--ink)]/10 text-[var(--ink)]') }}">
                                    {{ $row['status'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Jadwal teknisi hari ini --}}
        <div class="card rounded-2xl p-6">
            <p class="font-display font-600 mb-4">Jadwal teknisi — hari ini</p>
            <div class="space-y-4">
                @foreach([
                    ['time' => '09.00', 'task' => 'Survei atap — Rahmat H.', 'tech' => 'Teknisi: Bagas'],
                    ['time' => '13.00', 'task' => 'Pemasangan — Yusuf P.', 'tech' => 'Teknisi: Andra, Fikri'],
                    ['time' => '16.00', 'task' => 'Perawatan — CV Sinar Abadi', 'tech' => 'Teknisi: Bagas'],
                ] as $item)
                    <div class="flex gap-3">
                        <p class="font-mono text-xs text-soft w-12 pt-0.5">{{ $item['time'] }}</p>
                        <div class="flex-1 pb-4 border-b border-line last:border-0 last:pb-0">
                            <p class="text-sm font-medium">{{ $item['task'] }}</p>
                            <p class="text-xs text-soft mt-0.5">{{ $item['tech'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection
