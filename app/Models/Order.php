<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
use App\Models\User;

class Order extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_IN_PRODUCTION = 'in_production';
    public const STATUS_READY = 'ready';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    public const PAYMENT_PENDING = 'pending';
    public const PAYMENT_PAID = 'paid';
    public const PAYMENT_REFUNDED = 'refunded';

    public const PAYMENT_METHOD_COD = 'cod';
    public const PAYMENT_METHOD_PICKUP = 'pickup_cod';

    protected $fillable = [
        'user_id',
        'number',
        'status',
        'payment_status',
        'payment_method',
        'delivery_status',
        'delivery_type',
        'customer_name',
        'customer_email',
        'customer_phone',
        'address_line1',
        'address_line2',
        'postal_code',
        'city',
        'country',
        'notes',
        'subtotal',
        'delivery_fee',
        'total',
        'placed_at',
        'confirmed_at',
        'completed_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'total' => 'decimal:2',
        'placed_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $order) {
            if (empty($order->number)) {
                $order->number = Str::upper(Str::random(4)) . '-' . now()->format('mdHi');
            }
            if (empty($order->placed_at)) {
                $order->placed_at = now();
            }
        });
    }

    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING => 'En attente',
            self::STATUS_CONFIRMED => 'Confirmée',
            self::STATUS_IN_PRODUCTION => 'En préparation',
            self::STATUS_READY => 'Prête',
            self::STATUS_COMPLETED => 'Terminée',
            self::STATUS_CANCELLED => 'Annulée',
        ];
    }

    public static function paymentStatuses(): array
    {
        return [
            self::PAYMENT_PENDING => 'En attente',
            self::PAYMENT_PAID => 'Payée',
            self::PAYMENT_REFUNDED => 'Remboursée',
        ];
    }

    public static function paymentMethods(): array
    {
        return [
            self::PAYMENT_METHOD_COD => 'Paiement à la livraison',
            self::PAYMENT_METHOD_PICKUP => 'Paiement lors du retrait',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function delivery(): HasOne
    {
        return $this->hasOne(Delivery::class);
    }
}
