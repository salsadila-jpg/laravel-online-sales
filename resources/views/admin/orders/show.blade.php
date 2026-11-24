@extends('layouts.admin')

@section('title', 'Detail Transaksi')

@section('content')

<div class="admin-page-heading">
    <h1>Detail Transaksi</h1>
    <p>Informasi lengkap pesanan pelanggan.</p>
</div>

{{-- Tombol Kembali --}}
<div style="margin-bottom:20px">
    <a href="{{ route('admin.orders.index') }}" class="btn">
        Kembali ke Transaksi
    </a>
</div>

{{-- Informasi Pesanan --}}
<div class="card">
    <h3>Informasi Pesanan</h3>

    <div class="table-scroll">
        <table>
            <tbody>
                <tr>
                    <th>Kode Transaksi</th>
                    <td>{{ $order->code }}</td>
                </tr>

                <tr>
                    <th>Tanggal Pesanan</th>
                    <td>
                        {{ $order->ordered_at?->format('d-m-Y H:i') ?? '-' }}
                    </td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td>
                        <strong>{{ strtoupper($order->status) }}</strong>
                    </td>
                </tr>

                <tr>
                    <th>Subtotal</th>
                    <td>
                        Rp{{ number_format($order->subtotal, 2, ',', '.') }}
                    </td>
                </tr>

                <tr>
                    <th>Ongkos Kirim</th>
                    <td>
                        Rp{{ number_format($order->shipping_cost, 2, ',', '.') }}
                    </td>
                </tr>

                <tr>
                    <th>Total Pembayaran</th>
                    <td>
                        <strong>
                            Rp{{ number_format($order->grand_total, 2, ',', '.') }}
                        </strong>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

{{-- Informasi Pelanggan --}}
<div class="card">
    <h3>Informasi Pelanggan</h3>

    <div class="table-scroll">
        <table>
            <tbody>
                <tr>
                    <th>Nama Akun</th>
                    <td>{{ $order->user->name ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Nama Penerima</th>
                    <td>{{ $order->recipient_name }}</td>
                </tr>

                <tr>
                    <th>Nomor Telepon</th>
                    <td>{{ $order->phone }}</td>
                </tr>

                <tr>
                    <th>Alamat Pengiriman</th>
                    <td>{{ $order->address }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

{{-- Informasi Pengiriman --}}
<div class="card">
    <h3>Informasi Pengiriman</h3>

    <div class="table-scroll">
        <table>
            <tbody>
                <tr>
                    <th>Kurir</th>
                    <td>{{ $order->shipping_courier ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Layanan</th>
                    <td>{{ $order->shipping_service ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Estimasi Pengiriman</th>
                    <td>{{ $order->shipping_eta ?? '-' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

{{-- Produk yang Dibeli --}}
<div class="card">
    <h3>Produk yang Dibeli</h3>

    <div class="table-scroll">
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Subtotal</th>
                </tr>
            </thead>

            <tbody>
                @forelse($order->items as $item)
                    <tr>
                        <td>
                            {{ $item->product_name ?? $item->product?->name ?? '-' }}
                        </td>

                        <td>{{ $item->quantity }}</td>

                        <td>
                            Rp{{ number_format($item->price, 2, ',', '.') }}
                        </td>

                        <td>
                            Rp{{ number_format($item->subtotal, 2, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align:center">
                            Tidak ada produk dalam pesanan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Informasi Validasi --}}
<div class="card">
    <h3>Informasi Validasi</h3>

    <div class="table-scroll">
        <table>
            <tbody>
                <tr>
                    <th>Admin yang Memvalidasi</th>
                    <td>{{ $order->validator->name ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Waktu Validasi</th>
                    <td>
                        {{ $order->validated_at?->format('d-m-Y H:i') ?? '-' }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

{{-- Validasi Pesanan Pending --}}
@if($order->status === 'pending')

    <div class="card">
        <h3>Validasi Pesanan</h3>

        <p>
            Pesanan ini masih menunggu validasi admin.
        </p>

        <form
            method="POST"
            action="{{ route('admin.orders.confirm', $order) }}"
            onsubmit="return confirm('Apakah kamu yakin ingin memvalidasi pesanan ini?')"
        >
            @csrf

            <button type="submit" class="btn">
                Validasi Pesanan
            </button>
        </form>
    </div>

@endif

{{-- Perubahan Status Pesanan --}}
@if(in_array($order->status, ['confirmed', 'processed', 'shipped'], true))

    @php
        $nextStatus = [
            'confirmed' => 'processed',
            'processed' => 'shipped',
            'shipped' => 'completed',
        ];

        $next = $nextStatus[$order->status];
    @endphp

    <div class="card">
        <h3>Perbarui Status Pesanan</h3>

        <p>
            Status saat ini:
            <strong>{{ strtoupper($order->status) }}</strong>
        </p>

        <form
            method="POST"
            action="{{ route('admin.orders.updateStatus', $order) }}"
            onsubmit="return confirm('Apakah kamu yakin ingin mengubah status pesanan?')"
        >
            @csrf
            @method('PUT')

            <label for="status">Status berikutnya</label>

            <select class="input" name="status" id="status" required>
                <option value="{{ $next }}">
                    {{ strtoupper($next) }}
                </option>
            </select>

            <button type="submit" class="btn">
                Simpan Status
            </button>
        </form>
    </div>

@endif

{{-- Pembatalan Pesanan --}}
@if(in_array($order->status, ['pending', 'confirmed'], true))

    <div class="card">
        <h3>Batalkan Pesanan</h3>

        <p>
            Pembatalan akan mengembalikan stok produk yang ada
            dalam pesanan ke persediaan.
        </p>

        <form
            method="POST"
            action="{{ route('admin.orders.cancel', $order) }}"
            onsubmit="return confirm('Apakah kamu yakin ingin membatalkan pesanan ini?')"
        >
            @csrf

            <button
                type="submit"
                class="btn"
                style="background:#dc3545"
            >
                Batalkan Pesanan
            </button>
        </form>
    </div>

@endif

@endsection
