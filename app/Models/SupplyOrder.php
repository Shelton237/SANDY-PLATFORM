<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplyOrder extends Model
{
    use HasFactory;

    public const STATUSES = ['ordered', 'in_transit', 'received', 'quality_check'];

    protected $fillable = [
        'supplier_id',
        'reference',
        'ingredient',
        'quantity',
        'unit',
        'status',
        'transport_mode',
        'storage_location',
        'quality_status',
        'ordered_at',
        'expected_at',
        'received_at',
        'total_cost',
        'metadata',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'ordered_at' => 'date',
        'expected_at' => 'date',
        'received_at' => 'date',
        'metadata' => 'array',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
