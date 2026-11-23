@extends('layouts.admin')

@section('title', 'Ekspedisi')

@section('content')
<h1>Layanan Ekspedisi</h1>

<div class="card">
    <h2>Tambah Layanan</h2>

    <form method="POST" action="{{ route('admin.shipping.store') }}">
        @csrf

        <div class="row">
            <label>
                Kurir
                <input class="input" name="courier" maxlength="80" required>
            </label>

            <label>
                Layanan
                <input class="input" name="service" maxlength="80" required>
            </label>

            <label>
                Estimasi
                <input class="input" name="eta" maxlength="80">
            </label>

            <label>
                Biaya
                <input class="input" type="number" step="0.01"
                    min="0" name="cost" required>
            </label>

            <label>
                <input type="checkbox" name="is_active" value="1" checked>
                Aktif
            </label>

            <button class="btn" type="submit">Tambah</button>
        </div>
    </form>
</div>

<div class="card">
    <div class="table-scroll">
        <table>
            <thead>
                <tr>
                    <th>Kurir</th>
                    <th>Layanan</th>
                    <th>ETA</th>
                    <th>Biaya</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($services as $service)
                    <tr>
                        <td>{{ $service->courier }}</td>
                        <td>{{ $service->service }}</td>
                        <td>{{ $service->eta ?? '-' }}</td>
                        <td>Rp{{ number_format((float) $service->cost, 2, ',', '.') }}</td>
                        <td>{{ $service->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                        <td>
                            <details>
                                <summary>Ubah layanan</summary>

                                <form method="POST"
                                    action="{{ route('admin.shipping.update', $service) }}">
                                    @csrf
                                    @method('PUT')

                                    <label>
                                        Kurir
                                        <input class="input" name="courier"
                                            value="{{ $service->courier }}"
                                            maxlength="80" required>
                                    </label>

                                    <label>
                                        Layanan
                                        <input class="input" name="service"
                                            value="{{ $service->service }}"
                                            maxlength="80" required>
                                    </label>

                                    <label>
                                        Estimasi
                                        <input class="input" name="eta"
                                            value="{{ $service->eta }}"
                                            maxlength="80">
                                    </label>

                                    <label>
                                        Biaya
                                        <input class="input" name="cost" type="number"
                                            min="0" step="0.01"
                                            value="{{ $service->cost }}" required>
                                    </label>

                                    <label>
                                        <input type="checkbox" name="is_active"
                                            value="1" @checked($service->is_active)>
                                        Aktif
                                    </label>

                                    <button class="btn" type="submit">
                                        Simpan Perubahan
                                    </button>
                                </form>
                            </details>

                            @if($service->is_active)
                                <form method="POST"
                                    action="{{ route('admin.shipping.destroy', $service) }}">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-danger" type="submit">
                                        Nonaktifkan
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">Belum ada layanan ekspedisi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
