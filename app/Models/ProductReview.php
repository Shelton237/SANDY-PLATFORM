<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'author_name',
        'author_email',
        'rating',
        'comment',
        'is_visible',
        'metadata',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_visible' => 'boolean',
        'metadata' => 'array',
    ];

    protected static function booted(): void
    {
        $flush = fn () => Product::flushCatalogCache();

        static::saved($flush);
        static::deleted($flush);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }
}
