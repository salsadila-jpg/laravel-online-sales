<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            // Total seluruh produk
            'productCount' => Product::count(),

            // Produk dengan stok <= 5
            'lowStockCount' => Product::where('stock', '<=', 5)->count(),

            // Pesanan yang masih pending
            'pendingOrderCount' => Order::where('status', 'pending')->count(),

            // Total penjualan hari ini (pesanan batal tidak dihitung)
            'todaySales' => Order::whereDate('ordered_at', today())
                ->whereNotIn('status', ['cancelled'])
                ->sum('grand_total'),

            // 5 pesanan terbaru
            'latestOrders' => Order::with('user')
                ->latest('ordered_at')
                ->take(5)
                ->get(),
        ]);
    }
}
