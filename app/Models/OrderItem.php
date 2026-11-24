<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'price',
        'quantity',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    // Pesanan induk
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Produk terkait, jika masih tersedia di database
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
