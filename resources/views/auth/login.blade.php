@extends('layouts.guest')

@section('title', 'Masuk — Suryatama')

@section('content')

    <p class="font-mono text-xs tracking-widest uppercase text-amber-brand mb-2">Masuk</p>
    <h1 class="font-display text-2xl font-700 mb-8">Selamat datang kembali.</h1>

    @if (session('status'))
        <div class="mb-6 rounded-lg bg-[var(--teal)]/10 text-[var(--teal)] text-sm px-4 py-3">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium mb-1.5">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full rounded-lg border border-line px-4 py-2.5 text-sm bg-white">
            @error('email')
                <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-sm font-medium">Kata sandi</label>
                <a href="{{ route('password.request') }}" class="text-xs text-soft hover:text-[var(--ink)]">Lupa sandi?</a>
            </div>
            <input id="password" type="password" name="password" required
                   class="w-full rounded-lg border border-line px-4 py-2.5 text-sm bg-white">
            @error('password')
                <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <label class="flex items-center gap-2 text-sm text-soft">
            <input type="checkbox" name="remember" class="rounded border-line">
            Ingat saya
        </label>

        <button type="submit" class="btn-primary w-full rounded-lg py-3 text-sm font-semibold transition-colors">
            Masuk
        </button>
    </form>

    <p class="text-sm text-soft mt-8">
        Belum punya akun?
        <a href="{{ route('register') }}" class="text-[var(--ink)] font-semibold">Daftar di sini</a>
    </p>
@endsection
