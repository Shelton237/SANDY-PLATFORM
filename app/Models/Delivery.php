<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Delivery extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_SCHEDULED = 'scheduled';
    public const STATUS_DISPATCHED = 'dispatched';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_FAILED = 'failed';

    protected $fillable = [
        'order_id',
        'status',
        'scheduled_date',
        'time_window',
        'courier_name',
        'courier_phone',
        'vehicle_type',
        'tracking_link',
        'dispatched_at',
        'delivered_at',
        'notes',
        'address_line1',
        'address_line2',
        'postal_code',
        'city',
        'instructions',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'dispatched_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING => 'A planifier',
            self::STATUS_SCHEDULED => 'Planifiée',
            self::STATUS_DISPATCHED => 'En cours',
            self::STATUS_DELIVERED => 'Livrée',
            self::STATUS_FAILED => 'Incident',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
