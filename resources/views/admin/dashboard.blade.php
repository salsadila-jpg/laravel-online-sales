@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')

<h1>Dashboard</h1>

<div class="grid">
    <div class="card">
        <strong>{{ $productCount }}</strong>
        <br>
        Produk
    </div>

    <div class="card">
        <strong>{{ $lowStockCount }}</strong>
        <br>
        Stok &lt;= 5
    </div>

    <div class="card">
        <strong>{{ $pendingOrderCount }}</strong>
        <br>
        Pesanan Pending
    </div>

    <div class="card">
        <strong>Rp{{ number_format($todaySales, 2, ',', '.') }}</strong>
        <br>
        Penjualan Hari Ini
    </div>
</div>

<div class="card">
    <h3>Transaksi Terbaru</h3>

    <div class="table-scroll">
        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse($latestOrders as $order)
                    <tr>
                        <td>{{ $order->code }}</td>
                        <td>{{ $order->user->name ?? '-' }}</td>
                        <td>Rp{{ number_format($order->grand_total, 2, ',', '.') }}</td>
                        <td>{{ strtoupper($order->status) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Belum ada transaksi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
