<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_name',
        'email',
        'phone',
        'city',
        'country',
        'lead_time_days',
        'reliability_score',
        'expertise',
        'status',
        'certifications',
        'notes',
        'catalog',
    ];

    protected $casts = [
        'catalog' => 'array',
    ];

    public function supplyOrders(): HasMany
    {
        return $this->hasMany(SupplyOrder::class);
    }

    public function inventoryBatches(): HasMany
    {
        return $this->hasMany(InventoryBatch::class);
    }
}
