@extends('layouts.dashboard')

@section('title', 'Pengguna — Suryatama')
@section('page-title', 'Pengguna')

@php
    $authUser = auth()->user();
    $userName = $authUser->name;
    $userEmail = $authUser->email;
    $userRole = 'Administrator';
    $initials = strtoupper(collect(explode(' ', trim($userName)))
        ->filter()->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->implode('')) ?: 'A';

    $users = \App\Models\User::withCount('orders')->latest()->get();
@endphp

@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@section('content')

    @if(session('status'))
        <div class="mb-6 rounded-lg bg-teal-brand/10 text-teal-brand text-sm px-4 py-3">{{ session('status') }}</div>
    @endif

    <div class="card rounded-2xl overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-soft uppercase border-b border-line bg-black/[.015]">
                    <th class="p-4 font-medium">Nama</th>
                    <th class="p-4 font-medium">Email</th>
                    <th class="p-4 font-medium">Kota</th>
                    <th class="p-4 font-medium">Jumlah pesanan</th>
                    <th class="p-4 font-medium">Role</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-line">
                @foreach($users as $user)
                    <tr>
                        <td class="p-4 font-medium">{{ $user->name }}</td>
                        <td class="p-4 text-soft">{{ $user->email }}</td>
                        <td class="p-4 text-soft">{{ $user->city ?? '—' }}</td>
                        <td class="p-4 font-mono text-xs">{{ $user->orders_count }}</td>
                        <td class="p-4">
                            @if($user->id === $authUser->id)
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-[var(--ink)]/10 text-[var(--ink)]">{{ ucfirst($user->role) }} (kamu)</span>
                            @else
                                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                                    @csrf
                                    @method('PUT')
                                    <select name="role" onchange="this.form.submit()"
                                            class="text-xs font-semibold rounded-full px-3 py-1.5 border border-line bg-white">
                                        <option value="user" @selected($user->role === 'user')>User</option>
                                        <option value="admin" @selected($user->role === 'admin')>Admin</option>
                                    </select>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
