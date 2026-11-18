
@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
<div class="card auth-card">

    <span class="eyebrow">SELAMAT DATANG</span>

    <h1>Buat akun baru</h1>

    <p class="muted" style="text-align:left">
        Daftar sekarang dan mulai belanja kebutuhanmu dengan mudah.
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

    {{-- Form Register --}}
    <form method="POST" action="{{ route('register.process') }}">
        @csrf

        <label>
            Nama Lengkap
            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                placeholder="Masukkan nama lengkap"
                autocomplete="name"
                required
            >
        </label>

        <label>
            Email
            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="Masukkan email"
                autocomplete="email"
                required
            >
        </label>

        <label>
            Password
            <input
                type="password"
                name="password"
                placeholder="Masukkan password"
                autocomplete="new-password"
                required
            >
        </label>

        <label>
            Konfirmasi Password
            <input
                type="password"
                name="password_confirmation"
                placeholder="Ulangi password"
                autocomplete="new-password"
                required
            >
        </label>

        <button type="submit" class="btn">
            Daftar
        </button>
    </form>

    <p class="muted" style="margin-top:20px">
        Sudah punya akun?
        <a href="{{ route('login') }}">
            <strong>Masuk di sini</strong>
        </a>
    </p>

</div>
@endsection
