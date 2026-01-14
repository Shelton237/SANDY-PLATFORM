<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinanceAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type',
        'stage',
        'color',
        'description',
        'opening_balance',
        'allocated_budget',
        'is_active',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'allocated_budget' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(FinanceTransaction::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function findByCode(string $code): ?self
    {
        return static::where('code', $code)->first();
    }
}
