<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'user_id',
        'shipping_service_id',
        'recipient_name',
        'phone',
        'address',
        'shipping_courier',
        'shipping_service',
        'shipping_eta',
        'subtotal',
        'shipping_cost',
        'grand_total',
        'status',
        'ordered_at',
        'validated_by',
        'validated_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'grand_total' => 'decimal:2',
            'ordered_at' => 'datetime',
            'validated_at' => 'datetime',
        ];
    }

    // Pelanggan pemilik pesanan
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Detail produk dalam pesanan
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Layanan ekspedisi yang dipilih
    public function shippingService(): BelongsTo
    {
        return $this->belongsTo(ShippingService::class);
    }

    // Admin yang memvalidasi pesanan
    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }
}
