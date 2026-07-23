@php
    $currentRoute = Route::currentRouteName();
@endphp

<a href="{{ route('admin.dashboard') }}" class="nav-link {{ $currentRoute === 'admin.dashboard' ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="9"/><rect x="14" y="3" width="7" height="5"/><rect x="14" y="12" width="7" height="9"/><rect x="3" y="16" width="7" height="5"/></svg>
    Ringkasan
</a>
<a href="{{ route('admin.requests') }}" class="nav-link {{ $currentRoute === 'admin.requests' ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
    Permintaan Konsultasi
</a>
<a href="{{ route('admin.schedule') }}" class="nav-link {{ $currentRoute === 'admin.schedule' ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
    Jadwal Teknisi
</a>
<a href="{{ route('admin.users') }}" class="nav-link {{ $currentRoute === 'admin.users' ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="7" r="4"/><path d="M2 21v-1a7 7 0 0114 0v1"/><circle cx="18" cy="8" r="3"/><path d="M22 21v-1a5 5 0 00-4-4.9"/></svg>
    Pengguna
</a>
<a href="{{ route('admin.reports') }}" class="nav-link {{ $currentRoute === 'admin.reports' ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
    Laporan &amp; Invoice
</a>
