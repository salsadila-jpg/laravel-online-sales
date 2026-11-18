<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

// ==========================================
// HALAMAN UTAMA
// ==========================================

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
})->name('home');


// ==========================================
// KATALOG TOKO
// ==========================================

Route::get('/shop', function () {
    return view('shop.index');
})->name('shop.index');


// ==========================================
// LOGIN
// ==========================================

Route::get('/login', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return view('auth.login');
})->name('login');


Route::post('/login', function (Request $request) {

    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {

        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ])->onlyInput('email');

})->name('login.process');


// ==========================================
// REGISTER
// ==========================================

Route::get('/register', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return view('auth.register');
})->name('register');


Route::post('/register', function (Request $request) {

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email',
        'password' => [
            'required',
            'confirmed',
            Password::min(8),
        ],
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => 'user',
    ]);

    Auth::login($user);

    $request->session()->regenerate();

    return redirect()->route('dashboard');

})->name('register.process');


// ==========================================
// DASHBOARD
// ==========================================

Route::get('/dashboard', function () {

    if (!Auth::check()) {
        return redirect()->route('login');
    }

    return view('dashboard');

})->name('dashboard');


// ==========================================
// LOGOUT
// ==========================================

Route::post('/logout', function (Request $request) {

    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');

})->name('logout');
