<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\ShippingService;
use App\Models\StockMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /**
     * Menampilkan halaman checkout.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Keranjang masih kosong.');
        }

        $products = Product::whereIn('id', array_keys($cart))
            ->get()
            ->keyBy('id');

        $items = collect();
        $subtotal = 0;

        foreach ($cart as $productId => $cartItem) {
            $product = $products->get($productId);

            if (!$product || !$product->is_active) {
                continue;
            }

            $quantity = (int) $cartItem['quantity'];

            // Harga produk sudah dalam rupiah penuh.
            // Contoh: 50000 = Rp50.000
            $price = (float) $product->price;

            $lineTotal = $price * $quantity;

            $subtotal += $lineTotal;

            $items->push([
                'product' => $product,
                'quantity' => $quantity,
                'price' => $price,
                'lineTotal' => $lineTotal,
            ]);
        }

        if ($items->isEmpty()) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Produk dalam keranjang tidak ditemukan.');
        }

        return view('checkout.index', [
            'items' => $items,
            'subtotal' => $subtotal,
            'shippingServices' => ShippingService::where('is_active', true)
                ->orderBy('courier')
                ->get(),
        ]);
    }

    /**
     * Memproses checkout dan membuat pesanan.
     */
    public function store(CheckoutRequest $request): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Keranjang masih kosong.');
        }

        $order = DB::transaction(function () use ($request, $cart) {

            // Validasi ekspedisi aktif
            $shipping = ShippingService::whereKey(
                $request->integer('shipping_service_id')
            )
                ->where('is_active', true)
                ->firstOrFail();

            // Mengunci data produk selama checkout
            $lockedProducts = Product::whereIn('id', array_keys($cart))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $subtotal = 0;

            // Validasi produk dan stok
            foreach ($cart as $productId => $cartItem) {
                $product = $lockedProducts->get($productId);

                $quantity = (int) $cartItem['quantity'];

                if (
                    !$product ||
                    !$product->is_active ||
                    $quantity < 1 ||
                    $quantity > $product->stock
                ) {
                    throw ValidationException::withMessages([
                        'cart' => 'Stok berubah atau produk tidak tersedia. Periksa kembali keranjang Anda.',
                    ]);
                }

                // Harga database sudah dalam rupiah penuh
                $price = (float) $product->price;

                $subtotal += $price * $quantity;
            }

            // Ongkir dalam rupiah
            $shippingCost = (float) $shipping->cost;

            // Membuat kode pesanan unik
            $code = 'ORD-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));

            // Membuat pesanan
            $order = Order::create([
                'code' => $code,
                'user_id' => $request->user()->id,
                'shipping_service_id' => $shipping->id,

                'recipient_name' => $request->string('recipient_name')->toString(),
                'phone' => $request->string('phone')->toString(),
                'address' => $request->string('address')->toString(),

                'shipping_courier' => $shipping->courier,
                'shipping_service' => $shipping->service,
                'shipping_eta' => $shipping->eta,

                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'grand_total' => $subtotal + $shippingCost,

                'status' => 'pending',
                'ordered_at' => now(),
            ]);

            // Membuat item pesanan dan mengurangi stok
            foreach ($cart as $productId => $cartItem) {
                $product = $lockedProducts->get($productId);

                $quantity = (int) $cartItem['quantity'];

                // Harga produk dalam rupiah penuh
                $price = (float) $product->price;

                $lineTotal = $price * $quantity;

                // Menyimpan detail item pesanan
                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $price,
                    'quantity' => $quantity,
                    'subtotal' => $lineTotal,
                ]);

                // Mengurangi stok produk
                $product->decrement('stock', $quantity);

                // Mencatat pergerakan stok
                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => $request->user()->id,
                    'type' => 'OUT',
                    'quantity' => $quantity,
                    'reference' => $code,
                    'note' => 'Checkout pelanggan',
                    'movement_at' => now(),
                ]);
            }

            return $order;
        });

        // Mengosongkan keranjang setelah checkout berhasil
        $request->session()->forget('cart');

        // Mengarahkan ke detail pesanan
        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Checkout berhasil. Pesanan menunggu validasi admin.');
    }
}
