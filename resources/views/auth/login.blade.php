@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<div class="card auth-card">

    <span class="eyebrow">SELAMAT DATANG KEMBALI</span>

    <h1>Masuk ke akunmu</h1>

    <p class="muted" style="text-align:left">
        Lanjutkan belanja dan pantau pesanan dengan mudah.
    </p>

    {{-- Pesan sukses --}}
    @if (session('success'))
        <div style="color: green; margin-bottom: 15px;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Pesan error --}}
    @if ($errors->any())
        <div style="color: red; margin-bottom: 15px;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- Form Login --}}
    <form method="POST" action="{{ route('login.store') }}">
        @csrf

        <label>
            Email
            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="Masukkan email"
                autocomplete="username"
                required
                autofocus
            >
        </label>

        <label>
            Password
            <input
                type="password"
                name="password"
                placeholder="Masukkan password"
                autocomplete="current-password"
                required
            >
        </label>

        <button type="submit" class="btn">
            Masuk
        </button>
    </form>

    <p class="muted" style="margin-top:20px">
        Belum punya akun?
        <a href="{{ route('register') }}">
            <strong>Daftar di sini</strong>
        </a>
    </p>

</div>
@endsection
