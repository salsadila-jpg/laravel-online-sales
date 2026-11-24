@extends('layouts.app')

@section('title', 'Keranjang')

@section('content')

    <div class="section-heading">
        <div>
            <span class="eyebrow">SHOPPING CART</span>
            <h1>Keranjang Belanja</h1>
            <p class="muted">Periksa produk sebelum melanjutkan checkout.</p>
        </div>
    </div>

    {{-- NOTIFIKASI --}}
    @if (session('success'))
        <div class="alert">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert error">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert error">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- KERANJANG KOSONG --}}
    @if ($items->isEmpty())

        <div class="card empty-state">
            <h2>Keranjang kamu masih kosong</h2>
            <p class="muted">
                Yuk, temukan produk yang kamu butuhkan.
            </p>

            <a href="{{ route('shop.index') }}" class="btn">
                Belanja Sekarang
            </a>
        </div>

    @else

        {{-- DAFTAR PRODUK --}}
        <div class="card">
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                {{-- PRODUK --}}
                                <td>
                                    <strong>
                                        {{ $item['name'] }}
                                    </strong>
                                </td>

                                {{-- HARGA --}}
                                <td>
                                    Rp{{ number_format($item['price'], 0, ',', '.') }}
                                </td>

                                {{-- UPDATE JUMLAH --}}
                                <td>
                                    <form
                                        method="POST"
                                        action="{{ route('cart.update', $item['product_id']) }}"
                                        class="row"
                                    >
                                        @csrf
                                        @method('PUT')

                                        <input
                                            class="input"
                                            style="width: 85px"
                                            type="number"
                                            name="quantity"
                                            min="1"
                                            value="{{ $item['quantity'] }}"
                                            required
                                        >

                                        <button type="submit" class="btn">
                                            Update
                                        </button>
                                    </form>
                                </td>

                                {{-- SUBTOTAL --}}
                                <td>
                                    Rp{{ number_format($item['subtotal'], 0, ',', '.') }}
                                </td>

                                {{-- HAPUS --}}
                                <td>
                                    <form
                                        method="POST"
                                        action="{{ route('cart.destroy', $item['product_id']) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus produk ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn-danger">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- RINGKASAN BELANJA --}}
        <div class="card" style="margin-top: 20px">
            <h2>Ringkasan Belanja</h2>

            <div class="row" style="justify-content: space-between">
                <span>Total Produk</span>
                <strong>{{ $items->sum('quantity') }} item</strong>
            </div>

            <div class="row" style="justify-content: space-between">
                <span>Total Belanja</span>
                <h2 class="price">
                    Rp{{ number_format($total, 0, ',', '.') }}
                </h2>
            </div>

            <div class="row" style="margin-top: 20px">
                <a href="{{ route('shop.index') }}" class="btn-light">
                    Lanjut Belanja
                </a>

                <a href="{{ route('checkout.index') }}" class="btn">
                    Lanjut Checkout
                </a>
            </div>
        </div>

    @endif

@endsection
