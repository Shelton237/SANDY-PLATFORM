<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class LandingSection extends Model
{
    use HasFactory;

    public const HERO_CACHE_KEY = 'home:hero-section';

    protected $fillable = [
        'key',
        'title',
        'content',
        'is_active',
    ];

    protected $casts = [
        'content' => 'array',
        'is_active' => 'boolean',
    ];

    public function scopeKey($query, string $key)
    {
        return $query->where('key', $key);
    }

    protected static function booted(): void
    {
        static::saved(fn () => self::flushHeroCache());
        static::deleted(fn () => self::flushHeroCache());
    }

    public static function flushHeroCache(): void
    {
        Cache::forget(self::HERO_CACHE_KEY);
    }
}
