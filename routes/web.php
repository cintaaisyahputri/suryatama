<?php

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', function (Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Email atau kata sandi salah.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(
            Auth::user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard')
        );
    });

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    Route::post('/register', function (Request $request) {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'role' => 'user',
        ]);

        Auth::login($user);

        return redirect()->route('user.dashboard');
    });

    Route::get('/forgot-password', function () {
        return 'Halaman lupa password (buat sendiri sesuai kebutuhan, atau pasang Laravel Breeze).';
    })->name('password.request');
});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('home');
})->middleware('auth')->name('logout');

Route::get('/cek-status', function () {
    if (auth()->check()) {
        $u = auth()->user();

        return "SEDANG LOGIN sebagai: {$u->name} ({$u->email}), role: {$u->role}."
            . " <a href='/logout-paksa'>Klik di sini untuk logout paksa</a>,"
            . " lalu coba buka <a href='/login'>/login</a> lagi.";
    }

    return "TIDAK sedang login. Coba buka <a href='/login'>/login</a> — kalau di sini juga"
        . " tidak bisa dibuka/balik ke halaman utama, berarti bukan soal sesi.";
});

Route::get('/logout-paksa', function () {
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return "Sesi sudah dipaksa keluar. <a href='/login'>Coba /login sekarang</a>.";
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('user.dashboard');
    })->name('user.dashboard');

    Route::post('/dashboard/orders', function (Request $request) {
        auth()->user()->orders()->create([
            'capacity' => $request->input('capacity'),
            'city' => $request->input('city'),
            'address' => $request->input('address'),
        ]);

        return redirect()->route('user.dashboard')->with('status', 'Permintaan survei berhasil diajukan!');
    })->name('orders.store');

    Route::post('/tutorial/seen', function () {
        auth()->user()->update(['has_seen_tutorial' => true]);

        return response()->json(['ok' => true]);
    })->name('tutorial.seen');

    Route::get('/jadwal-survei', function () {
        return view('user.schedule');
    })->name('user.schedule');

    Route::get('/produksi-listrik', function () {
        return view('user.production');
    })->name('user.production');

    Route::get('/invoice', function () {
        return view('user.invoices');
    })->name('user.invoices');

    Route::get('/profil', function () {
        return view('user.profile');
    })->name('user.profile');

    Route::put('/profil', function (Request $request) {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,'.auth()->id()],
            'phone' => ['nullable', 'string', 'max:30'],
            'city' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        auth()->user()->update($data);

        return back()->with('status', 'Profil berhasil diperbarui.');
    })->name('user.profile.update');

    Route::put('/profil/password', function (Request $request) {
        $data = $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', 'min:6'],
        ]);

        if (! Hash::check($data['current_password'], auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini salah.']);
        }

        auth()->user()->update(['password' => Hash::make($data['password'])]);

        return back()->with('status', 'Kata sandi berhasil diperbarui.');
    })->name('user.password.update');

});

Route::middleware('auth')->prefix('admin')->group(function () {

    Route::get('/', function () {
        abort_unless(auth()->user()->role === 'admin', 403);

        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/permintaan', function () {
        abort_unless(auth()->user()->role === 'admin', 403);

        return view('admin.requests');
    })->name('admin.requests');

    Route::put('/permintaan/{order}', function (Request $request, Order $order) {
        abort_unless(auth()->user()->role === 'admin', 403);

        $request->validate([
            'status' => ['required', 'in:menunggu_survei,survei_terjadwal,pemasangan,aktif'],
        ]);

        $order->update(['status' => $request->input('status')]);

        return back()->with('status', 'Status pesanan berhasil diperbarui.');
    })->name('admin.requests.update');

    Route::delete('/permintaan/{order}', function (Order $order) {
        abort_unless(auth()->user()->role === 'admin', 403);

        $order->delete();

        return back()->with('status', 'Pesanan berhasil dihapus.');
    })->name('admin.requests.destroy');

    Route::get('/jadwal-teknisi', function () {
        abort_unless(auth()->user()->role === 'admin', 403);

        return view('admin.schedule');
    })->name('admin.schedule');

    Route::put('/jadwal-teknisi/{order}', function (Request $request, Order $order) {
        abort_unless(auth()->user()->role === 'admin', 403);

        $data = $request->validate([
            'scheduled_at' => ['nullable', 'date'],
            'technician_name' => ['nullable', 'string', 'max:255'],
        ]);

        $order->update($data);

        return back()->with('status', 'Jadwal berhasil disimpan.');
    })->name('admin.schedule.update');

    Route::get('/pengguna', function () {
        abort_unless(auth()->user()->role === 'admin', 403);

        return view('admin.users');
    })->name('admin.users');

    Route::put('/pengguna/{user}', function (Request $request, User $user) {
        abort_unless(auth()->user()->role === 'admin', 403);

        $request->validate(['role' => ['required', 'in:user,admin']]);

        $user->update(['role' => $request->input('role')]);

        return back()->with('status', 'Role pengguna berhasil diperbarui.');
    })->name('admin.users.update');

    Route::delete('/pengguna/{user}', function (User $user) {
        abort_unless(auth()->user()->role === 'admin', 403);

        if ($user->id === auth()->id()) {
            return back()->withErrors(['user' => 'Kamu tidak bisa menghapus akunmu sendiri.']);
        }

        $user->delete();

        return back()->with('status', 'Pengguna berhasil dihapus.');
    })->name('admin.users.destroy');

    Route::get('/laporan', function () {
        abort_unless(auth()->user()->role === 'admin', 403);

        return view('admin.reports');
    })->name('admin.reports');

});