@extends('layouts.app')

@section('title', 'Pilihan untuk keseharian')

@section('content')

{{-- HERO SECTION --}}
<section class="hero">
    <div class="hero-content">
        <span class="eyebrow">PILIHAN UNTUK KESEHARIAN</span>

        <h1>Hal kecil yang bikin<br>hari kamu lebih nyaman.</h1>

        <p>
            Temukan kebutuhan rumah, aksesori, dan elektronik pilihan.
            <br>
            Harga jelas, stok tersedia, siap menemani aktivitasmu.
        </p>

        <div class="hero-features">
            <span>Informasi stok langsung</span>
            <span>Pilihan ekspedisi</span>
            <span>Riwayat pesanan lengkap</span>
        </div>
    </div>

    <div class="hero-visual">
        <div class="hero-icon">⌨️</div>
        <div class="hero-icon">🖱️</div>
    </div>
</section>


{{-- JUDUL DAN JUMLAH PRODUK --}}
<div class="section-heading">
    <h2>Temukan pilihanmu</h2>

    <span class="muted">
        {{ $products->total() }} produk ditemukan
    </span>
</div>


{{-- FILTER PRODUK --}}
<div class="card filters">
    <form action="{{ route('shop.index') }}" method="GET">

        <div class="filter-grid">

            {{-- SEARCH --}}
            <div class="filter-group">
                <label for="search">Cari produk</label>

                <input
                    type="search"
                    id="search"
                    name="search"
                    placeholder="Cari nama atau SKU produk..."
                    value="{{ request('search', $search ?? '') }}"
                >
            </div>

            {{-- KATEGORI --}}
            <div class="filter-group">
                <label for="category">Kategori</label>

                <select name="category" id="category">
                    <option value="">Semua kategori</option>

                    @foreach($categories ?? [] as $category)
                        <option
                            value="{{ $category->id }}"
                            {{ request('category') == $category->id ? 'selected' : '' }}
                        >
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- SORTING --}}
            <div class="filter-group">
                <label for="sort">Urutkan</label>

                <select name="sort" id="sort">
                    <option value="latest"
                        {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>
                        Terbaru
                    </option>

                    <option value="price_low"
                        {{ request('sort') == 'price_low' ? 'selected' : '' }}>
                        Harga terendah
                    </option>

                    <option value="price_high"
                        {{ request('sort') == 'price_high' ? 'selected' : '' }}>
                        Harga tertinggi
                    </option>

                    <option value="name"
                        {{ request('sort') == 'name' ? 'selected' : '' }}>
                        Nama produk A-Z
                    </option>
                </select>
            </div>

            {{-- HARGA MINIMUM --}}
            <div class="filter-group">
                <label for="min_price">Harga minimum</label>

                <input
                    type="number"
                    id="min_price"
                    name="min_price"
                    placeholder="Harga minimum"
                    min="0"
                    value="{{ request('min_price') }}"
                >
            </div>

            {{-- HARGA MAKSIMUM --}}
            <div class="filter-group">
                <label for="max_price">Harga maksimum</label>

                <input
                    type="number"
                    id="max_price"
                    name="max_price"
                    placeholder="Harga maksimum"
                    min="0"
                    value="{{ request('max_price') }}"
                >
            </div>

            {{-- STOK --}}
            <div class="filter-group filter-stock">
                <label>
                    <input
                        type="checkbox"
                        name="stock"
                        value="1"
                        {{ request('stock') ? 'checked' : '' }}
                    >
                    Stok tersedia
                </label>
            </div>

            {{-- BUTTON FILTER --}}
            <div class="filter-actions">
                <button type="submit" class="btn">
                    Terapkan Filter
                </button>

                <a href="{{ route('shop.index') }}" class="reset-link">
                    Reset
                </a>
            </div>

        </div>
    </form>
</div>


{{-- DAFTAR PRODUK --}}
<div class="grid product-grid">

    @forelse($products as $product)

        <article class="card product-card">

            {{-- FOTO PRODUK --}}
            <div class="product-image-wrapper">
                @if($product->image)
                    <img
                        class="thumb"
                        src="{{ asset('storage/' . $product->image) }}"
                        alt="{{ $product->name }}"
                        loading="lazy"
                    >
                @else
                    <div class="thumb product-placeholder">
                        📦
                    </div>
                @endif
            </div>

            {{-- INFORMASI PRODUK --}}
            <div class="product-info">

                <h3>{{ $product->name }}</h3>

                <p class="price">
                    Rp{{ number_format($product->price, 0, ',', '.') }}
                </p>

                <p class="stock {{ $product->stock <= 0 ? 'empty' : '' }}">
                    @if($product->stock > 0)
                        {{ $product->stock }} stok tersedia
                    @else
                        Stok habis
                    @endif
                </p>

                {{-- TAMBAH KE KERANJANG --}}
                @if($product->stock > 0)

                    @auth
                        @if(auth()->user()->role === 'customer')

                            <form
                                action="{{ route('cart.store', $product->id) }}"
                                method="POST"
                            >
                                @csrf

                                <input
                                    type="hidden"
                                    name="quantity"
                                    value="1"
                                >

                                <button type="submit" class="btn" style="width:100%">
                                    + Tambah ke Keranjang
                                </button>
                            </form>

                        @else

                            <p class="muted">
                                Login sebagai pelanggan untuk berbelanja.
                            </p>

                        @endif
                    @else

                        <a
                            href="{{ route('login') }}"
                            class="btn"
                            style="width:100%; text-align:center"
                        >
                            Masuk untuk Belanja
                        </a>

                    @endauth

                @else

                    <button
                        type="button"
                        class="btn-light"
                        disabled
                        style="width:100%"
                    >
                        Stok Habis
                    </button>

                @endif

            </div>
        </article>

    @empty

        {{-- PRODUK TIDAK DITEMUKAN --}}
        <div class="empty-state">
            <h3>Produk tidak ditemukan</h3>

            <p class="muted">
                Coba kata kunci lain atau sesuaikan filter pencarianmu.
            </p>

            <a href="{{ route('shop.index') }}" class="btn-light">
                Lihat Semua Produk
            </a>
        </div>

    @endforelse

</div>


{{-- PAGINATION --}}
<div class="pagination-wrapper">
    {{ $products->appends(request()->query())->links() }}
</div>

@endsection
