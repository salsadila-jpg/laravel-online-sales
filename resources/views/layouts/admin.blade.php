<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') · Admin Ruang Belanja</title>
    <link rel="stylesheet" href="{{ asset('css/store.css') }}">
</head>
<body>

<div class="admin-wrap">

    <aside class="admin-side">

        <a class="brand" href="{{ route('admin.categories.index') }}">
            ruangbelanja.
        </a>

        <div class="eyebrow">Ruang Pengelola</div>

        <a href="{{ route('admin.categories.index') }}"
            @if(request()->routeIs('admin.categories.*'))
                aria-current="page"
            @endif>
            Kategori
        </a>

        <a href="{{ route('admin.products.index') }}"
            @if(request()->routeIs('admin.products.*'))
                aria-current="page"
            @endif>
            Produk
        </a>

        @if(Route::has('admin.stock.index'))
            <a href="{{ route('admin.stock.index') }}"
                @if(request()->routeIs('admin.stock.*'))
                    aria-current="page"
                @endif>
                Stok & Barang Masuk
            </a>
        @endif

        @if(Route::has('admin.shipping.index'))
            <a href="{{ route('admin.shipping.index') }}"
                @if(request()->routeIs('admin.shipping.*'))
                    aria-current="page"
                @endif>
                Ekspedisi
            </a>
        @endif

        @if(Route::has('admin.orders.index'))
            <a href="{{ route('admin.orders.index') }}"
                @if(request()->routeIs('admin.orders.*'))
                    aria-current="page"
                @endif>
                Transaksi
            </a>
        @endif

        <a href="{{ route('shop.index') }}">
            ↗ Lihat Toko
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-light">
                Keluar
            </button>
        </form>

    </aside>

    <main class="admin-main">

        <div class="admin-context">
            <span>
                Administrasi / @yield('title', 'Dashboard')
            </span>

            <span>
                {{ auth()->user()->name }} · Admin
            </span>
        </div>

        @include('layouts.flash')

        @yield('content')

    </main>

</div>

<script src="{{ asset('js/store.js') }}" defer></script>

</body>
</html>
