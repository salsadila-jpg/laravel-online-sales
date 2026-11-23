@extends('layouts.app')

@section('title', 'Keranjang')

@section('content')
    <h1>Keranjang Belanja</h1>

    @if (session('success'))
        <div class="card">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="card">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    @if ($items->isEmpty())
        <div class="card">
            <p>Keranjang kosong.</p>
            <a href="{{ route('shop.index') }}" class="btn">
                Belanja Sekarang
            </a>
        </div>
    @else
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
                            <td>
                                {{ $item['product']->name }}
                            </td>

                            <td>
                                Rp{{ number_format($item['product']->price, 0, ',', '.') }}
                            </td>

                            <td>
                                <form
                                    method="POST"
                                    action="{{ route('cart.update', $item['product']) }}"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <input
                                        style="width: 70px"
                                        type="number"
                                        name="quantity"
                                        min="1"
                                        max="{{ $item['product']->stock }}"
                                        value="{{ $item['quantity'] }}"
                                        required
                                    >

                                    <button type="submit" class="btn">
                                        Update
                                    </button>
                                </form>
                            </td>

                            <td>
                                Rp{{ number_format($item['lineTotal'], 0, ',', '.') }}
                            </td>

                            <td>
                                <form
                                    method="POST"
                                    action="{{ route('cart.destroy', $item['product']) }}"
                                    onsubmit="return confirm('Yakin ingin menghapus produk ini dari keranjang?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card" style="margin-top: 18px">
            <h3>
                Subtotal:
                Rp{{ number_format($subtotal, 0, ',', '.') }}
            </h3>

            <a href="{{ route('shop.index') }}" class="btn">
                Lanjut Belanja
            </a>

            @if (Route::has('checkout.index'))
                <a href="{{ route('checkout.index') }}" class="btn">
                    Lanjut Checkout
                </a>
            @endif
        </div>
    @endif
@endsection
