@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<h1>Checkout</h1>

<div class="checkout-layout">
    {{-- Form Data Penerima --}}
    <div class="card">
        <form method="POST" action="{{ route('checkout.store') }}">
            @csrf

            <label>
                Nama Penerima
                <input
                    class="input"
                    name="recipient_name"
                    value="{{ old('recipient_name', auth()->user()->name) }}"
                    required
                >
            </label>

            <label>
                No. Telepon
                <input
                    class="input"
                    name="phone"
                    value="{{ old('phone') }}"
                    required
                >
            </label>

            <label>
                Alamat
                <textarea
                    class="input"
                    rows="4"
                    name="address"
                    required
                >{{ old('address') }}</textarea>
            </label>

            <label>
                Ekspedisi
                <select
                    class="input"
                    name="shipping_service_id"
                    id="shipping_service_id"
                    required
                >
                    <option value="">Pilih ekspedisi</option>

                    @foreach($shippingServices as $service)
                        <option
                            value="{{ $service->id }}"
                            data-cost="{{ $service->cost }}"
                            @selected(old('shipping_service_id') == $service->id)
                        >
                            {{ $service->courier }} -
                            {{ $service->service }}
                            ({{ $service->eta }}) -
                            Rp{{ number_format($service->cost, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
            </label>

            <button
                type="submit"
                class="btn"
                @disabled($shippingServices->isEmpty())
            >
                Buat Pesanan
            </button>

            @if($shippingServices->isEmpty())
                <p class="validation-error">
                    Belum ada layanan pengiriman aktif.
                    Hubungi admin sebelum checkout.
                </p>
            @endif
        </form>
    </div>

    {{-- Ringkasan Pesanan --}}
    <div class="card">
        <h3>Ringkasan Pesanan</h3>

        @foreach($items as $item)
            <p>
                {{ $item['product']->name }}
                x {{ $item['quantity'] }} =
                Rp{{ number_format($item['lineTotal'] * 1000, 0, ',', '.') }}
            </p>
        @endforeach

        <hr>

        {{-- Subtotal dalam rupiah --}}
        <div
            id="subtotal"
            data-subtotal="{{ $subtotal * 1000 }}"
        >
            <strong>
                Subtotal:
                Rp{{ number_format($subtotal * 1000, 0, ',', '.') }}
            </strong>
        </div>

        {{-- Ongkir --}}
        <div class="summary-line">
            <span>Ongkos kirim</span>
            <strong id="shipping-total">
                Pilih ekspedisi
            </strong>
        </div>

        <hr>

        {{-- Total pembayaran --}}
        <div class="summary-total" id="order-total">
            <strong>
                Total: Rp{{ number_format($subtotal * 1000, 0, ',', '.') }}
            </strong>
        </div>

        <p class="muted">
            Ongkir ditambahkan sesuai ekspedisi yang dipilih.
        </p>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const shippingSelect = document.getElementById('shipping_service_id');
    const shippingTotal = document.getElementById('shipping-total');
    const orderTotal = document.getElementById('order-total');
    const subtotalElement = document.getElementById('subtotal');

    const subtotal = Number(subtotalElement.dataset.subtotal);

    function formatRupiah(value) {
        return 'Rp' + new Intl.NumberFormat('id-ID', {
            maximumFractionDigits: 0
        }).format(value);
    }

    function calculateTotal() {
        const selectedOption = shippingSelect.options[shippingSelect.selectedIndex];

        const shippingCost = selectedOption && selectedOption.dataset.cost
            ? Number(selectedOption.dataset.cost)
            : 0;

        const grandTotal = subtotal + shippingCost;

        shippingTotal.textContent = shippingCost > 0
            ? formatRupiah(shippingCost)
            : 'Pilih ekspedisi';

        orderTotal.innerHTML =
            '<strong>Total: ' + formatRupiah(grandTotal) + '</strong>';
    }

    shippingSelect.addEventListener('change', calculateTotal);

    // Hitung otomatis saat halaman dibuka
    calculateTotal();
});
</script>
@endpush
