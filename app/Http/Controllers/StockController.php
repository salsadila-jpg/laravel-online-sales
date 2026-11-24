<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StockController extends Controller
{
    /**
     * Menampilkan histori pergerakan stok.
     */
    public function index(): View
    {
        $movements = StockMovement::with('product')
            ->latest('movement_at')
            ->paginate(10);

        $products = Product::orderBy('name')->get();

        return view('admin.stock.index', compact(
            'movements',
            'products'
        ));
    }

    /**
     * Menampilkan form barang masuk.
     */
    public function create(): View
    {
        $products = Product::orderBy('name')->get();

        return view('admin.stock.create', compact('products'));
    }

    /**
     * Menyimpan transaksi barang masuk.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => [
                'required',
                'exists:products,id',
            ],
            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],
            'note' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        DB::transaction(function () use ($validated) {
            $product = Product::whereKey($validated['product_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $product->increment('stock', $validated['quantity']);

            StockMovement::create([
                'product_id' => $product->id,
                'type' => 'IN',
                'quantity' => $validated['quantity'],
                'reference' => null,
                'note' => $validated['note'] ?? 'Barang masuk',
                'movement_at' => now(),
            ]);
        });

        return redirect()
            ->route('admin.stock.index')
            ->with('success', 'Stok berhasil ditambahkan.');
    }
}

