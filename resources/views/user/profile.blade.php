@extends('layouts.dashboard')

@section('title', 'Profil — Suryatama')
@section('page-title', 'Profil')

@php
    $authUser = auth()->user();
    $userName = $authUser->name;
    $userEmail = $authUser->email;
    $userRole = 'Pelanggan';
    $initials = strtoupper(collect(explode(' ', trim($userName)))
        ->filter()->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->implode('')) ?: 'U';
@endphp

@section('sidebar')
    @include('partials.sidebar-user')
@endsection

@section('content')

    @if(session('status'))
        <div class="mb-6 rounded-lg bg-teal-brand/10 text-teal-brand text-sm px-4 py-3">
            {{ session('status') }}
        </div>
    @endif

    <div class="max-w-xl">
        <div class="card rounded-2xl p-7">
            <p class="text-xs uppercase tracking-wide text-soft mb-6">Informasi akun</p>

            <form method="POST" action="{{ route('user.profile.update') }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-medium mb-1.5">Nama lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $authUser->name) }}"
                           class="w-full rounded-lg border border-line px-4 py-2.5 text-sm bg-white">
                    @error('name') <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email', $authUser->email) }}"
                           class="w-full rounded-lg border border-line px-4 py-2.5 text-sm bg-white">
                    @error('email') <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium mb-1.5">Nomor WhatsApp</label>
                    <input type="text" name="phone" value="{{ old('phone', $authUser->phone) }}"
                           class="w-full rounded-lg border border-line px-4 py-2.5 text-sm bg-white">
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium mb-1.5">Kota</label>
                        <input type="text" name="city" value="{{ old('city', $authUser->city) }}"
                               class="w-full rounded-lg border border-line px-4 py-2.5 text-sm bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-medium mb-1.5">Alamat</label>
                        <input type="text" name="address" value="{{ old('address', $authUser->address) }}"
                               class="w-full rounded-lg border border-line px-4 py-2.5 text-sm bg-white">
                    </div>
                </div>

                <p class="text-xs text-soft">Alamat & kota ini yang dipakai sebagai lokasi default saat mengajukan survei baru.</p>

                <button type="submit" class="btn-primary rounded-lg px-6 py-3 text-sm font-semibold transition-colors">
                    Simpan perubahan
                </button>
            </form>
        </div>

        <div class="card rounded-2xl p-7 mt-6">
            <p class="text-xs uppercase tracking-wide text-soft mb-6">Ganti kata sandi</p>
            <form method="POST" action="{{ route('user.password.update') }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-medium mb-1.5">Kata sandi saat ini</label>
                    <input type="password" name="current_password" class="w-full rounded-lg border border-line px-4 py-2.5 text-sm bg-white">
                    @error('current_password') <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium mb-1.5">Kata sandi baru</label>
                    <input type="password" name="password" class="w-full rounded-lg border border-line px-4 py-2.5 text-sm bg-white">
                    @error('password') <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium mb-1.5">Konfirmasi kata sandi baru</label>
                    <input type="password" name="password_confirmation" class="w-full rounded-lg border border-line px-4 py-2.5 text-sm bg-white">
                </div>

                <button type="submit" class="border border-line rounded-lg px-6 py-3 text-sm font-semibold hover:bg-black/[.02]">
                    Update kata sandi
                </button>
            </form>
        </div>
    </div>

@endsection
