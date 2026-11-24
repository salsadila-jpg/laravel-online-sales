<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Online Sales')</title>

    {{-- Font Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- CSS Utama --}}
    <link rel="stylesheet" href="{{ asset('css/store.css') }}">

    {{-- CSS Tambahan --}}
    @stack('styles')
</head>

<body>

    {{-- TOP NOTE --}}
    <div class="top-note">
        Online Sales - Pilihan untuk Keseharian
    </div>

    {{-- HEADER / NAVBAR --}}
    <header class="site-header">
        <div class="container nav">

            {{-- BRAND --}}
            <a href="{{ route('home') }}" class="brand">
                <span class="brand-mark">O</span>
                <span>Online Sales</span>
            </a>

            {{-- MENU NAVIGASI --}}
            <a href="{{ route('home') }}"
               @if (request()->routeIs('home')) aria-current="page" @endif>
                Beranda
            </a>

            <a href="{{ route('shop.index') }}"
               @if (request()->routeIs('shop.*')) aria-current="page" @endif>
                Produk
            </a>

            {{-- KERANJANG --}}
            <a href="{{ route('cart.index') }}"
               @if (request()->routeIs('cart.*')) aria-current="page" @endif>
                Keranjang
                ({{ collect(session('cart', []))->sum('quantity') }})
            </a>

            {{-- MENU PENGGUNA --}}
            @auth

                @if (auth()->user()->role === 'admin')

                    <a href="{{ route('admin.dashboard') }}"
                       @if (request()->routeIs('admin.dashboard')) aria-current="page" @endif>
                        Dashboard
                    </a>

                @else

                    <a href="{{ route('orders.index') }}"
                       @if (request()->routeIs('orders.*')) aria-current="page" @endif>
                        Pesanan Saya
                    </a>

                    <a href="{{ route('profile.edit') }}"
                       @if (request()->routeIs('profile.*')) aria-current="page" @endif>
                        Profil
                    </a>

                @endif

                <span class="account">
                    {{ auth()->user()->name }}
                </span>

                {{-- LOGOUT --}}
                <form action="{{ route('logout') }}" method="POST">
                    @csrf

                    <button type="submit" class="btn-danger">
                        Logout
                    </button>
                </form>

            @endauth

        </div>
    </header>

    {{-- KONTEN UTAMA --}}
    <main>
        <div class="container">

            {{-- NOTIFIKASI BERHASIL --}}
            @if (session('success'))
                <div class="alert">
                    {{ session('success') }}
                </div>
            @endif

            {{-- NOTIFIKASI ERROR --}}
            @if (session('error'))
                <div class="alert error">
                    {{ session('error') }}
                </div>
            @endif

            {{-- VALIDASI ERROR --}}
            @if ($errors->any())
                <div class="alert error">
                    <strong>Terjadi kesalahan:</strong>

                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ISI HALAMAN --}}
            @yield('content')

        </div>
    </main>

    {{-- FOOTER --}}
    <footer class="container footer">
        <div>
            &copy; {{ date('Y') }} Online Sales.
            All rights reserved.
        </div>

        <div>
            Belanja mudah, aman, dan nyaman.
        </div>
    </footer>

    {{-- JAVASCRIPT --}}
    @stack('scripts')

</body>
</html>