<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ProductCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'tagline',
        'description',
        'icon',
        'accent',
        'hero_image',
        'is_active',
        'is_featured',
        'position',
        'metadata',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'position' => 'integer',
        'metadata' => 'array',
    ];

    public const NAV_CACHE_KEY = 'nav:categories';
    public const HOME_FEATURED_CACHE_KEY = 'home:featured-categories';

    protected static function booted(): void
    {
        static::saving(function (self $category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::saved(fn () => self::flushCaches());
        static::deleted(fn () => self::flushCaches());
    }

    public static function flushCaches(): void
    {
        Cache::forget(self::NAV_CACHE_KEY);
        Cache::forget(self::HOME_FEATURED_CACHE_KEY);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category', 'slug');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
