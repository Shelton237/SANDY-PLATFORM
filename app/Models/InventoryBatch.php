<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'batch_code',
        'ingredient',
        'quantity_available',
        'unit',
        'status',
        'location',
        'reserved_for',
        'received_at',
        'expires_at',
        'metadata',
    ];

    protected $casts = [
        'quantity_available' => 'decimal:2',
        'received_at' => 'date',
        'expires_at' => 'date',
        'metadata' => 'array',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function productionBatches(): HasMany
    {
        return $this->hasMany(ProductionBatch::class);
    }
}
