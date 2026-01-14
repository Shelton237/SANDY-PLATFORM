<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionBatch extends Model
{
    use HasFactory;

    public const STATUSES = ['planned', 'in_progress', 'quality_check', 'packaged'];

    protected $fillable = [
        'product_id',
        'code',
        'inventory_batch_id',
        'status',
        'planned_liters',
        'actual_liters',
        'yield_percent',
        'team_lead',
        'shift',
        'starts_at',
        'ends_at',
        'notes',
        'ingredients_used',
        'quality_checks',
    ];

    protected $casts = [
        'planned_liters' => 'decimal:2',
        'actual_liters' => 'decimal:2',
        'yield_percent' => 'integer',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'ingredients_used' => 'array',
        'quality_checks' => 'array',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function inventoryBatch(): BelongsTo
    {
        return $this->belongsTo(InventoryBatch::class);
    }
}
