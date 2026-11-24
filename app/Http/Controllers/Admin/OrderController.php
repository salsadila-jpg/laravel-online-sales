<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar transaksi.
     */
    public function index(Request $request): View
    {
        $orders = Order::with('user')
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->input('status'));
            })
            ->when($request->filled('q'), function ($q) use ($request) {
                $keyword = $request->string('q')->toString();

                $q->where(function ($sub) use ($keyword) {
                    $sub->where('code', 'like', "%{$keyword}%")
                        ->orWhereHas('user', function ($u) use ($keyword) {
                            $u->where('name', 'like', "%{$keyword}%");
                        });
                });
            })
            ->latest('ordered_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Menampilkan detail transaksi.
     */
    public function show(Order $order): View
    {
        $order->load([
            'user',
            'items',
            'validator',
        ]);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Memvalidasi pesanan Pending.
     */
    public function confirm(
        Request $request,
        Order $order
    ): RedirectResponse {

        if ($order->status !== 'pending') {
            return back()->with(
                'error',
                'Hanya pesanan pending yang dapat divalidasi.'
            );
        }

        $order->update([
            'status' => 'confirmed',
            'validated_by' => $request->user()->id,
            'validated_at' => now(),
        ]);

        return back()->with(
            'success',
            'Pesanan berhasil divalidasi.'
        );
    }

    /**
     * Memperbarui status pesanan secara berurutan.
     */
    public function updateStatus(
        Request $request,
        Order $order
    ): RedirectResponse {

        $data = $request->validate([
            'status' => [
                'required',
                Rule::in([
                    'processed',
                    'shipped',
                    'completed',
                ]),
            ],
        ]);

        // Alur perubahan status yang diperbolehkan.
        $allowed = [
            'confirmed' => ['processed'],
            'processed' => ['shipped'],
            'shipped' => ['completed'],
        ];

        // Memeriksa apakah perubahan status sesuai alur.
        if (! in_array(
            $data['status'],
            $allowed[$order->status] ?? [],
            true
        )) {
            return back()->with(
                'error',
                'Perubahan status tidak sesuai alur transaksi.'
            );
        }

        // Menyimpan status baru.
        $order->update([
            'status' => $data['status'],
        ]);

        return back()->with(
            'success',
            'Status pesanan berhasil diperbarui menjadi '
                . strtoupper($data['status']) . '.'
        );
    }

    /**
     * Membatalkan pesanan dan mengembalikan stok.
     */
    public function cancel(
        Request $request,
        Order $order
    ): RedirectResponse {

        if (! in_array(
            $order->status,
            ['pending', 'confirmed'],
            true
        )) {
            return back()->with(
                'error',
                'Pesanan pada status ini tidak dapat dibatalkan.'
            );
        }

        DB::transaction(function () use ($request, $order) {

            $order->load('items');

            foreach ($order->items as $item) {

                // Lewati jika produk tidak ditemukan.
                if (! $item->product_id) {
                    continue;
                }

                // Mengunci data produk selama transaksi.
                $product = Product::whereKey($item->product_id)
                    ->lockForUpdate()
                    ->first();

                if (! $product) {
                    continue;
                }

                // Mengembalikan stok produk.
                $product->increment('stock', $item->quantity);

                // Mencatat pergerakan stok.
                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => $request->user()->id,
                    'type' => 'IN',
                    'quantity' => $item->quantity,
                    'reference' => 'CANCEL-' . $order->code,
                    'note' => 'Pengembalian stok karena pesanan dibatalkan admin',
                    'movement_at' => now(),
                ]);
            }

            // Mengubah status menjadi cancelled.
            $order->update([
                'status' => 'cancelled',
                'validated_by' => $request->user()->id,
                'validated_at' => now(),
            ]);
        });

        return back()->with(
            'success',
            'Pesanan berhasil dibatalkan dan stok dikembalikan.'
        );
    }
}
