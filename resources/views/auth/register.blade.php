@extends('layouts.guest')

@section('title', 'Daftar — Suryatama')

@section('content')

    <p class="font-mono text-xs tracking-widest uppercase text-amber-brand mb-2">Daftar</p>
    <h1 class="font-display text-2xl font-700 mb-8">Buat akun untuk mulai survei.</h1>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium mb-1.5">Nama lengkap</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                   class="w-full rounded-lg border border-line px-4 py-2.5 text-sm bg-white">
            @error('name')
                <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium mb-1.5">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                   class="w-full rounded-lg border border-line px-4 py-2.5 text-sm bg-white">
            @error('email')
                <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="phone" class="block text-sm font-medium mb-1.5">Nomor WhatsApp</label>
            <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required placeholder="08xx-xxxx-xxxx"
                   class="w-full rounded-lg border border-line px-4 py-2.5 text-sm bg-white">
            @error('phone')
                <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium mb-1.5">Kata sandi</label>
            <input id="password" type="password" name="password" required
                   class="w-full rounded-lg border border-line px-4 py-2.5 text-sm bg-white">
            @error('password')
                <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium mb-1.5">Konfirmasi kata sandi</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                   class="w-full rounded-lg border border-line px-4 py-2.5 text-sm bg-white">
        </div>

        <button type="submit" class="btn-primary w-full rounded-lg py-3 text-sm font-semibold transition-colors">
            Buat akun
        </button>
    </form>

    <p class="text-sm text-soft mt-8">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="text-[var(--ink)] font-semibold">Masuk di sini</a>
    </p>
@endsection
