<?php

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman utama
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('home');
})->name('home');


/*
|--------------------------------------------------------------------------
| Auth: login & register asli (pakai Auth facade bawaan Laravel)
|--------------------------------------------------------------------------
| Tidak perlu Laravel Breeze/Fortify untuk ini — cukup Auth facade & model
| User bawaan Laravel (asalkan sudah pasang kolom & relasi dari
| app/Models/User.php.patch dan migration di database/migrations).
*/
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

    // Dummy "lupa password" supaya link di halaman login tidak error
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


/*
|--------------------------------------------------------------------------
| Halaman user (butuh login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('user.dashboard');
    })->name('user.dashboard');

    // Simpan permintaan survei pertama dari pelanggan baru
    Route::post('/dashboard/orders', function (Request $request) {
        auth()->user()->orders()->create([
            'capacity' => $request->input('capacity'),
            'city' => $request->input('city'),
            'address' => $request->input('address'),
        ]);

        return redirect()->route('user.dashboard')->with('status', 'Permintaan survei berhasil diajukan!');
    })->name('orders.store');

    // Menandai tutorial sudah dilihat (dipanggil otomatis oleh komponen <x-tutorial>)
    Route::post('/tutorial/seen', function () {
        auth()->user()->update(['has_seen_tutorial' => true]);

        return response()->json(['ok' => true]);
    })->name('tutorial.seen');

    // Jadwal survei
    Route::get('/jadwal-survei', function () {
        return view('user.schedule');
    })->name('user.schedule');

    // Produksi listrik
    Route::get('/produksi-listrik', function () {
        return view('user.production');
    })->name('user.production');

    // Invoice
    Route::get('/invoice', function () {
        return view('user.invoices');
    })->name('user.invoices');

    // Profil
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


/*
|--------------------------------------------------------------------------
| Halaman admin (butuh login + role admin)
|--------------------------------------------------------------------------
| Belum pakai middleware `role` khusus supaya tidak perlu daftar middleware
| tambahan dulu — cukup dicek langsung di dalam closure-nya (abort_unless).
*/
Route::middleware('auth')->prefix('admin')->group(function () {

    Route::get('/', function () {
        abort_unless(auth()->user()->role === 'admin', 403);

        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Permintaan konsultasi: daftar semua pesanan + ubah status
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

    // Jadwal teknisi: atur tanggal kunjungan & nama teknisi per pesanan
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

    // Pengguna: daftar semua akun + ubah role
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

    // Laporan & invoice
    Route::get('/laporan', function () {
        abort_unless(auth()->user()->role === 'admin', 403);

        return view('admin.reports');
    })->name('admin.reports');

});
