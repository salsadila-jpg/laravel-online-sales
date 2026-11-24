<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Dashboard Admin') - Admin Toko Online</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('css/store.css') }}">
</head>
<body>

    {{-- NAVBAR ADMIN --}}
    <header class="navbar">
        <div class="container navbar-content">

            <a href="{{ route('admin.dashboard') }}" class="navbar-brand">
                Admin Toko Online
            </a>

            <div class="navbar-menu">

                @auth
                    <span class="navbar-user">
                        Halo, {{ Auth::user()->name }}
                    </span>

                    <form action="{{ route('logout') }}"
                          method="POST"
                          style="display: inline;">
                        @csrf

                        <button type="submit" class="btn btn-danger">
                            Logout
                        </button>
                    </form>
                @endauth

            </div>
        </div>
    </header>


    {{-- LAYOUT ADMIN --}}
    <div class="container admin-layout">

        {{-- SIDEBAR --}}
        <aside class="admin-sidebar">

            <div class="card sidebar-card">

                <h3>Menu Admin</h3>

                <nav class="admin-nav">

                    <a href="{{ route('admin.dashboard') }}"
                       class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>

                    <a href="{{ route('admin.products.index') }}"
                       class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                        Produk
                    </a>

                    <a href="{{ route('admin.categories.index') }}"
                       class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        Kategori
                    </a>

                    <a href="{{ route('admin.orders.index') }}"
                       class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                        Pesanan
                    </a>

                    <a href="{{ route('admin.shipping-services.index') }}"
                       class="{{ request()->routeIs('admin.shipping-services.*') ? 'active' : '' }}">
                        Layanan Pengiriman
                    </a>

                    <a href="{{ route('admin.stocks.index') }}"
                       class="{{ request()->routeIs('admin.stocks.*') ? 'active' : '' }}">
                        Stok
                    </a>

                </nav>

            </div>

        </aside>


        {{-- KONTEN UTAMA --}}
        <main class="admin-content">

            {{-- JUDUL HALAMAN --}}
            <div class="admin-page-heading">
                <h1>@yield('heading', 'Dashboard Admin')</h1>
            </div>


            {{-- FLASH MESSAGE SUKSES --}}
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif


            {{-- FLASH MESSAGE ERROR --}}
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif


            {{-- VALIDASI ERROR --}}
            @if ($errors->any())
                <div class="alert alert-danger">
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

        </main>

    </div>


    {{-- FOOTER --}}
    <footer class="footer">
        <div class="container">
            <p>
                &copy; {{ date('Y') }} Admin Toko Online.
                All Rights Reserved.
            </p>
        </div>
    </footer>


    {{-- JAVASCRIPT --}}
    <script src="{{ asset('js/store.js') }}"></script>

    @stack('scripts')

</body>
</html>
