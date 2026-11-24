<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Memproses login pengguna.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => 'Email atau password yang dimasukkan salah.',
            ]);
        }

        // Regenerasi session setelah login berhasil.
        $request->session()->regenerate();

        // Arahkan berdasarkan role pengguna.
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Customer langsung menuju katalog produk.
        return redirect()->intended(route('shop.index'));
    }
}
