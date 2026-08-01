
@php
    $notifUser = auth()->user();
    $notifItems = collect();

    if ($notifUser) {
        if ($notifUser->role === 'admin') {
            $notifItems = \App\Models\Order::with('user')
                ->where('status', 'menunggu_survei')
                ->latest()
                ->take(8)
                ->get()
                ->map(fn (\App\Models\Order $o) => [
                    'message' => 'Permintaan baru dari '.($o->user->name ?? 'pelanggan').' — '.($o->capacity ?? 'kapasitas belum diisi'),
                    'time' => $o->created_at,
                    'url' => route('admin.requests'),
                ]);
        } else {
            $orderItems = $notifUser->orders()->latest()->take(5)->get()
                ->map(fn (\App\Models\Order $o) => [
                    'message' => 'Pesanan #SRY-'.(2000 + $o->id).' sekarang: '.$o->statusLabel(),
                    'time' => $o->updated_at,
                    'url' => route('user.schedule'),
                ]);

            $invoiceItems = \App\Models\Invoice::whereHas('order', fn ($q) => $q->where('user_id', $notifUser->id))
                ->whereNull('paid_at')
                ->latest('due_date')
                ->take(5)
                ->get()
                ->map(fn (\App\Models\Invoice $i) => [
                    'message' => 'Invoice '.$i->label.' jatuh tempo '.optional($i->due_date)->translatedFormat('d M Y'),
                    'time' => $i->due_date,
                    'url' => route('user.invoices'),
                ]);

            $notifItems = $orderItems->concat($invoiceItems);
        }

        $notifItems = $notifItems->filter(fn ($i) => $i['time'])->sortByDesc('time')->take(8)->values();
    }

    $notifCount = $notifItems->count();
    $notifId = 'notif-'.uniqid();
@endphp

<div class="relative">
    <button type="button" class="notif-toggle relative text-soft" aria-label="Notifikasi" data-panel="{{ $notifId }}">
        <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8a6 6 0 00-12 0c0 7-3 9-3 9h18s-3-2-3-9M13.7 21a2 2 0 01-3.4 0"/></svg>
        @if($notifCount > 0)
            <span class="absolute -top-0.5 -right-0.5 w-2 h-2 rounded-full bg-amber-brand"></span>
        @endif
    </button>

    <div id="{{ $notifId }}" class="notif-panel hidden absolute right-0 mt-3 w-80 bg-white border border-line rounded-2xl shadow-xl z-50 overflow-hidden">
        <div class="px-4 py-3 border-b border-line flex items-center justify-between">
            <p class="text-sm font-semibold">Notifikasi</p>
            @if($notifCount > 0)
                <span class="text-xs text-soft">{{ $notifCount }} baru</span>
            @endif
        </div>
        <div class="max-h-80 overflow-y-auto divide-y divide-line">
            @forelse($notifItems as $item)
                <a href="{{ $item['url'] }}" class="block px-4 py-3 text-sm hover:bg-black/[.02] transition-colors">
                    <p class="text-[var(--ink)] leading-snug">{{ $item['message'] }}</p>
                    <p class="text-xs text-soft mt-1">{{ optional($item['time'])->diffForHumans() ?? '—' }}</p>
                </a>
            @empty
                <p class="px-4 py-8 text-sm text-soft text-center">Tidak ada notifikasi baru.</p>
            @endforelse
        </div>
    </div>
</div>

@once
    @push('scripts')
    <script>
        document.addEventListener('click', function (e) {
            const toggle = e.target.closest('.notif-toggle');

            if (toggle) {
                const panel = document.getElementById(toggle.dataset.panel);
                const isHidden = panel.classList.contains('hidden');
                document.querySelectorAll('.notif-panel').forEach(function (p) { p.classList.add('hidden'); });
                if (isHidden) panel.classList.remove('hidden');
                return;
            }

            if (!e.target.closest('.notif-panel')) {
                document.querySelectorAll('.notif-panel').forEach(function (p) { p.classList.add('hidden'); });
            }
        });
    </script>
    @endpush
@endonce
