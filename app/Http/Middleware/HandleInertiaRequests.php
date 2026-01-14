<?php

namespace App\Http\Middleware;

use App\Models\ProductCategory;
use App\Support\Cart\CartManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'navCategories' => Cache::remember(
                'nav:categories',
                now()->addMinutes(10),
                function () {
                    return ProductCategory::query()
                        ->active()
                        ->withCount([
                            'products as products_count' => fn ($query) => $query->published(),
                        ])
                        ->having('products_count', '>', 0)
                        ->orderBy('position')
                        ->orderBy('name')
                        ->get()
                        ->map(fn (ProductCategory $category) => [
                            'id' => $category->id,
                            'slug' => $category->slug,
                            'name' => $category->name,
                            'description' => $category->description,
                            'icon' => $category->icon ?? 'bi bi-droplet',
                            'accent' => $category->accent,
                            'tagline' => $category->tagline,
                            'products_count' => $category->products_count,
                        ])
                        ->values()
                        ->all();
                }
            ),
            'cartCount' => fn () => app(CartManager::class)->count(),
            'shop' => [
                'currency' => config('shop.currency', 'FCFA'),
                'delivery_fee' => (float) config('shop.delivery_fee', 0),
                'free_delivery_threshold' => (float) config('shop.free_delivery_threshold', 0),
                'max_quantity' => (int) config('shop.max_quantity', 12),
            ],
            'flash' => fn () => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
                'info' => $request->session()->get('info'),
            ],
        ];
    }
}
