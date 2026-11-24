<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Toko Online') - Toko Online</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('css/store.css') }}">
</head>
<body>

    {{-- Navbar --}}
    <header class="navbar">
        <div class="container navbar-content">

            <a href="{{ url('/') }}" class="navbar-brand">
                Toko Online
            </a>

            <nav class="navbar-menu">
                <a href="{{ url('/') }}">Beranda</a>

                <a href="{{ route('products.index') }}">
                    Produk
                </a>

                @auth
                    <a href="{{ route('cart.index') }}">
                        Keranjang
                    </a>

                    <a href="{{ route('orders.index') }}">
                        Pesanan Saya
                    </a>

                    <span class="navbar-user">
                        Halo, {{ Auth::user()->name }}
                    </span>

                    {{-- Logout menggunakan POST --}}
                    <form action="{{ route('logout') }}"
                          method="POST"
                          style="display: inline;">
                        @csrf

                        <button type="submit" class="btn btn-danger">
                            Logout
                        </button>
                    </form>

                @else
                    <a href="{{ route('login') }}">Login</a>

                    <a href="{{ route('register') }}">
                        Register
                    </a>
                @endauth
            </nav>

        </div>
    </header>

    {{-- Flash Message --}}
    <main class="container">

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Konten Halaman --}}
        @yield('content')

    </main>

    {{-- Footer --}}
    <footer class="footer">
        <div class="container">
            <p>
                &copy; {{ date('Y') }} Toko Online.
                All Rights Reserved.
            </p>
        </div>
    </footer>

    {{-- JavaScript --}}
    <script src="{{ asset('js/store.js') }}"></script>

    @stack('scripts')

</body>
</html>
