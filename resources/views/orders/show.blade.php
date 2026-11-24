@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
<div class="order-detail-page">

    {{-- Header --}}
    <div class="row" style="justify-content: space-between; align-items: center;">
        <h1>Detail Pesanan</h1>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">
            Kembali ke Pesanan
        </a>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    {{-- Informasi Pesanan --}}
    <div class="card">
        <h2>Pesanan {{ $order->code }}</h2>

        <p>
            Status:
            <span class="badge">
                {{ strtoupper($order->status) }}
            </span>
        </p>

        <p>
            <strong>Tanggal Pesanan:</strong>
            {{ $order->ordered_at ? \Carbon\Carbon::parse($order->ordered_at)->format('d M Y, H:i') : '-' }}
        </p>
    </div>

    {{-- Informasi Penerima --}}
    <div class="card">
        <h3>Informasi Penerima</h3>

        <p>
            <strong>Nama:</strong>
            {{ $order->recipient_name }}
        </p>

        <p>
            <strong>No. Telepon:</strong>
            {{ $order->phone }}
        </p>

        <p>
            <strong>Alamat:</strong>
            {{ $order->address }}
        </p>
    </div>

    {{-- Informasi Pengiriman --}}
    <div class="card">
        <h3>Informasi Pengiriman</h3>

        <p>
            <strong>Ekspedisi:</strong>
            {{ $order->shipping_courier }}
            {{ $order->shipping_service }}
        </p>

        <p>
            <strong>Estimasi:</strong>
            {{ $order->shipping_eta }}
        </p>

        <p>
            <strong>Ongkir:</strong>
            Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}
        </p>
    </div>

    {{-- Daftar Produk --}}
    <div class="card">
        <h3>Produk yang Dipesan</h3>

        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>

                            <td>
                                Rp{{ number_format($item->price * 1000, 0, ',', '.') }}
                            </td>

                            <td>{{ $item->quantity }}</td>

                            <td>
                                Rp{{ number_format($item->subtotal * 1000, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Ringkasan Pembayaran --}}
    <div class="card">
        <h3>Ringkasan Pembayaran</h3>

        <div class="summary-line">
            <span>Subtotal Produk</span>
            <strong>
                Rp{{ number_format($order->subtotal * 1000, 0, ',', '.') }}
            </strong>
        </div>

        <div class="summary-line">
            <span>Ongkos Kirim</span>
            <strong>
                Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}
            </strong>
        </div>

        <hr>

        <div class="summary-total">
            <h2>
                Total Pembayaran:
                Rp{{ number_format(($order->subtotal * 1000) + $order->shipping_cost, 0, ',', '.') }}
            </h2>
        </div>
    </div>

    {{-- Status Pesanan --}}
    <div class="card">
        <h3>Status Pesanan</h3>

        @if($order->status === 'pending')
            <p>
                Pesanan kamu berhasil dibuat dan sedang menunggu validasi admin.
            </p>
        @elseif($order->status === 'processing')
            <p>
                Pesanan sedang diproses.
            </p>
        @elseif($order->status === 'shipped')
            <p>
                Pesanan sudah dikirim.
            </p>
        @elseif($order->status === 'completed')
            <p>
                Pesanan telah selesai.
            </p>
        @elseif($order->status === 'cancelled')
            <p>
                Pesanan dibatalkan.
            </p>
        @else
            <p>
                Status pesanan: {{ ucfirst($order->status) }}
            </p>
        @endif
    </div>

</div>
@endsection
