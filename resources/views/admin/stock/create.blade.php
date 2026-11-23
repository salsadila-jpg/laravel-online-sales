@extends('layouts.admin')

@section('title', 'Barang Masuk')

@section('content')
<h1>Barang Masuk / Penyetokan</h1>

<div class="card">
    <form method="POST" action="{{ route('admin.stock.store') }}">
        @csrf

        <label>
            Produk
            <select name="product_id" required>
                @foreach($products as $product)
                    <option value="{{ $product->id }}"
                        @selected(old('product_id') == $product->id)>
                        {{ $product->sku }} - {{ $product->name }}
                        (stok {{ $product->stock }})
                    </option>
                @endforeach
            </select>
        </label>

        <label>
            Jumlah
            <input class="input" type="number" min="1"
                name="quantity" value="{{ old('quantity') }}" required>
        </label>

        <label>
            Harga Beli per Unit
            <input class="input" type="number" min="0" step="0.01"
                name="unit_cost" value="{{ old('unit_cost') }}">
        </label>

        <label>
            Referensi / No. Faktur
            <input class="input" name="reference"
                maxlength="120" value="{{ old('reference') }}">
        </label>

        <label>
            Tanggal Masuk
            <input class="input" type="datetime-local" name="movement_at"
                value="{{ old('movement_at', now()->format('Y-m-d\TH:i')) }}"
                required>
        </label>

        <label>
            Catatan
            <textarea class="input" name="note">{{ old('note') }}</textarea>
        </label>

        <button class="btn" type="submit">Simpan Barang Masuk</button>
        <a class="btn" href="{{ route('admin.stock.index') }}">Kembali</a>
    </form>
</div>
@endsection
