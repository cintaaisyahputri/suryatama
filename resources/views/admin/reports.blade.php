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
    $orders = \App\Models\Order::with('user')->latest()->get();

    $totalLunas = $invoices->filter->isPaid()->sum('amount');
    $totalMenunggu = $invoices->reject->isPaid()->sum('amount');
@endphp

@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@section('content')

    @if(session('status'))
        <div class="mb-6 rounded-lg bg-teal-brand/10 text-teal-brand text-sm px-4 py-3">{{ session('status') }}</div>
    @endif

    @foreach($errors->all() as $error)
        <div class="mb-6 rounded-lg bg-red-500/10 text-red-600 text-sm px-4 py-3">{{ $error }}</div>
    @endforeach

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

    <details class="card rounded-2xl mb-6">
        <summary class="cursor-pointer list-none p-5 flex items-center justify-between font-display font-600">
            <span>+ Buat invoice baru</span>
            <span class="text-xs text-soft font-normal font-sans">Untuk pesanan yang sudah ada</span>
        </summary>
        <div class="px-5 pb-6 pt-1 border-t border-line">
            @if($orders->isEmpty())
                <p class="text-sm text-soft mt-4">Belum ada pesanan untuk dibuatkan invoice.</p>
            @else
                <form method="POST" action="{{ route('admin.invoices.store') }}" class="mt-4 grid sm:grid-cols-2 gap-4 max-w-2xl">
                    @csrf
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-medium mb-1.5">Pesanan</label>
                        <select name="order_id" id="invoice-order-select" required onchange="suryatamaUpdateMinimum(this)"
                                class="w-full rounded-lg border border-line px-4 py-2.5 text-sm bg-white">
                            <option value="" data-minimum="0">— Pilih pesanan —</option>
                            @foreach($orders as $order)
                                <option value="{{ $order->id }}" data-minimum="{{ $order->minimumPrice() }}">
                                    #SRY-{{ 2000 + $order->id }} · {{ $order->user->name ?? '—' }} · {{ $order->capacity ?? '—' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium mb-1.5">Label</label>
                        <input type="text" name="label" required placeholder="Contoh: DP Pemasangan"
                               class="w-full rounded-lg border border-line px-4 py-2.5 text-sm bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-medium mb-1.5">Jumlah (Rp)</label>
                        <input type="number" name="amount" id="invoice-amount-input" required min="0" step="1000" placeholder="3500000"
                               class="w-full rounded-lg border border-line px-4 py-2.5 text-sm bg-white">
                        <p id="invoice-minimum-hint" class="text-xs text-soft mt-1.5">Pilih pesanan dulu untuk lihat jumlah minimum.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium mb-1.5">Jatuh tempo</label>
                        <input type="date" name="due_date" class="w-full rounded-lg border border-line px-4 py-2.5 text-sm bg-white">
                    </div>
                    <button type="submit" class="btn-primary sm:col-span-2 rounded-lg py-3 text-sm font-semibold transition-colors">
                        Buat invoice
                    </button>
                </form>
            @endif
        </div>
    </details>

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
                        <th class="p-4 font-medium"></th>
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
                            <td class="p-4 text-right whitespace-nowrap">
                                @unless($invoice->isPaid())
                                    <form method="POST" action="{{ route('admin.invoices.mark-paid', $invoice) }}" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="text-xs font-semibold text-teal-brand hover:underline mr-4">Tandai lunas</button>
                                    </form>
                                @endunless
                                <form method="POST" action="{{ route('admin.invoices.destroy', $invoice) }}" class="inline"
                                      onsubmit="return confirm('Hapus invoice ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-semibold text-red-600 hover:text-red-700">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @push('scripts')
    <script>
        function suryatamaUpdateMinimum(select) {
            var option = select.options[select.selectedIndex];
            var minimum = parseInt(option.getAttribute('data-minimum') || '0', 10);
            var input = document.getElementById('invoice-amount-input');
            var hint = document.getElementById('invoice-minimum-hint');

            if (minimum > 0) {
                input.setAttribute('min', minimum);
                hint.textContent = 'Minimal Rp ' + minimum.toLocaleString('id-ID') + ' untuk kapasitas pesanan ini.';
            } else {
                input.removeAttribute('min');
                hint.textContent = 'Pilih pesanan dulu untuk lihat jumlah minimum.';
            }
        }
    </script>
    @endpush

@endsection