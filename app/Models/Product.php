<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Product extends Model
{
    use HasFactory;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_ARCHIVED = 'archived';

    protected $fillable = [
        'name',
        'slug',
        'category',
        'tagline',
        'description',
        'price',
        'size',
        'availability',
        'stock',
        'status',
        'is_limited',
        'is_new',
        'energy_index',
        'calories',
        'sugars',
        'fibers',
        'ingredients',
        'benefits',
        'moments',
        'taste_notes',
        'badge',
        'accent',
        'image_path',
        'batch_note',
        'metadata',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_limited' => 'boolean',
        'is_new' => 'boolean',
        'energy_index' => 'integer',
        'calories' => 'integer',
        'sugars' => 'integer',
        'fibers' => 'decimal:2',
        'ingredients' => 'array',
        'benefits' => 'array',
        'moments' => 'array',
        'taste_notes' => 'array',
        'metadata' => 'array',
        'stock' => 'integer',
    ];

    public static function statuses(): array
    {
        return [
            self::STATUS_PUBLISHED => 'Publié',
            self::STATUS_DRAFT => 'Brouillon',
            self::STATUS_ARCHIVED => 'Archivé',
        ];
    }

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    protected static function booted(): void
    {
        static::saving(function (self $product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name ?? uniqid('product_', true));
            }
        });
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function productionBatches(): HasMany
    {
        return $this->hasMany(ProductionBatch::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('position')->orderBy('id');
    }
}
