<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    // Menampilkan halaman keranjang
    public function index(Request $request): View
    {
        $cart = $request->session()->get('cart', []);

        $items = collect($cart)->map(function ($item) {
            $item['subtotal'] = $item['price'] * $item['quantity'];

            return $item;
        });

        $total = $items->sum('subtotal');

        return view('cart.index', [
            'items' => $items,
            'total' => $total,
        ]);
    }

    // Menambahkan produk ke keranjang
    public function store(
        Request $request,
        Product $product
    ): RedirectResponse {

        abort_unless($product->is_active, 404);

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $quantity = $validated['quantity'];

        if ($product->stock < $quantity) {
            return back()->with(
                'error',
                'Stok produk tidak mencukupi.'
            );
        }

        $cart = $request->session()->get('cart', []);

        $currentQuantity = $cart[$product->id]['quantity'] ?? 0;

        if ($currentQuantity + $quantity > $product->stock) {
            return back()->with(
                'error',
                'Jumlah produk melebihi stok tersedia.'
            );
        }

        $cart[$product->id] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'image' => $product->image,
            'quantity' => $currentQuantity + $quantity,
        ];

        $request->session()->put('cart', $cart);

        return redirect()
            ->route('cart.index')
            ->with(
                'success',
                'Produk berhasil ditambahkan ke keranjang.'
            );
    }

    // Mengubah jumlah produk
    public function update(
        Request $request,
        int $productId
    ): RedirectResponse {

        $cart = $request->session()->get('cart', []);

        if (!isset($cart[$productId])) {
            return back()->with('error', 'Produk tidak ditemukan.');
        }

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($productId);

        if ($validated['quantity'] > $product->stock) {
            return back()->with('error', 'Stok produk tidak mencukupi.');
        }

        $cart[$productId]['quantity'] = $validated['quantity'];

        $request->session()->put('cart', $cart);

        return back()->with('success', 'Keranjang berhasil diperbarui.');
    }

    // Menghapus produk dari keranjang
    public function destroy(
        Request $request,
        int $productId
    ): RedirectResponse {

        $cart = $request->session()->get('cart', []);

        unset($cart[$productId]);

        $request->session()->put('cart', $cart);

        return back()->with('success', 'Produk berhasil dihapus.');
    }

    // Mengosongkan keranjang
    public function clear(Request $request): RedirectResponse
    {
        $request->session()->forget('cart');

        return back()->with('success', 'Keranjang berhasil dikosongkan.');
    }
}
