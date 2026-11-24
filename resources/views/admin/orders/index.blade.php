@extends('layouts.admin')

@section('title', 'Transaksi')

@section('content')

<div class="admin-page-heading">
    <h1>Transaksi</h1>
    <p>Kelola dan pantau seluruh transaksi pelanggan.</p>
</div>

{{-- Form pencarian dan filter --}}
<div class="card">
    <form class="row" method="GET" action="{{ route('admin.orders.index') }}">

        <input
            class="input"
            style="max-width:280px"
            type="text"
            name="q"
            value="{{ request('q') }}"
            placeholder="Cari kode / pelanggan"
        >

        <select class="input" name="status">
            <option value="">Semua status</option>

            @foreach([
                'pending',
                'confirmed',
                'processed',
                'shipped',
                'completed',
                'cancelled'
            ] as $status)

                <option
                    value="{{ $status }}"
                    @selected(request('status') === $status)
                >
                    {{ ucfirst($status) }}
                </option>

            @endforeach
        </select>

        <button class="btn" type="submit">
            Filter
        </button>

        <a class="btn" href="{{ route('admin.orders.index') }}">
            Reset
        </a>

    </form>
</div>

{{-- Tabel transaksi --}}
<div class="card">
    <div class="table-scroll">
        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Pelanggan</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>{{ $order->code }}</td>

                        <td>
                            {{ $order->user->name ?? '-' }}
                        </td>

                        <td>
                            {{ $order->ordered_at?->format('d-m-Y H:i') ?? '-' }}
                        </td>

                        <td>
                            Rp{{ number_format($order->grand_total, 2, ',', '.') }}
                        </td>

                        <td>
                            <span class="status-badge status-{{ $order->status }}">
                                {{ strtoupper($order->status) }}
                            </span>
                        </td>

                        <td>
                            <a
                                class="btn"
                                href="{{ route('admin.orders.show', $order) }}"
                            >
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center">
                            Transaksi tidak ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:20px">
        {{ $orders->links() }}
    </div>
</div>

@endsection
