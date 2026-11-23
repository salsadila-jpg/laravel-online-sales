<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShippingServiceController extends Controller
{
    public function index(): View
    {
        return view('admin.shipping.index', [
            'services' => ShippingService::orderBy('courier')
                ->orderBy('service')
                ->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'courier' => ['required', 'string', 'max:80'],
            'service' => ['required', 'string', 'max:80'],
            'eta' => ['nullable', 'string', 'max:80'],
            'cost' => ['required', 'numeric', 'min:0'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        ShippingService::create($data);

        return back()->with(
            'success',
            'Layanan ekspedisi ditambahkan.'
        );
    }

    public function update(
        Request $request,
        ShippingService $shippingService
    ): RedirectResponse {
        $data = $request->validate([
            'courier' => ['required', 'string', 'max:80'],
            'service' => ['required', 'string', 'max:80'],
            'eta' => ['nullable', 'string', 'max:80'],
            'cost' => ['required', 'numeric', 'min:0'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $shippingService->update($data);

        return back()->with(
            'success',
            'Layanan ekspedisi diperbarui.'
        );
    }

    public function destroy(
        ShippingService $shippingService
    ): RedirectResponse {
        $shippingService->update([
            'is_active' => false,
        ]);

        return back()->with(
            'success',
            'Layanan ekspedisi dinonaktifkan.'
        );
    }
}
