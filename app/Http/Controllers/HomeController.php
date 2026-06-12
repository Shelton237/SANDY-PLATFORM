<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(): Response
    {
        $products = Cache::remember(
            Product::HOME_FEATURED_CACHE_KEY,
            now()->addMinutes(5),
            function () {
                return Product::query()
                    ->published()
                    ->with(['images' => fn ($q) => $q->orderBy('position')->orderBy('id')])
                    ->latest('created_at')
                    ->take(8)
                    ->get()
                    ->map(fn (Product $p) => [
                        'id'       => $p->id,
                        'name'     => $p->name,
                        'slug'     => $p->slug,
                        'tagline'  => $p->tagline,
                        'price'    => (float) $p->price,
                        'is_new'   => $p->is_new,
                        'image'    => $p->images->first()?->url ?? $p->image_path,
                        'accent'   => $p->accent,
                    ])
                    ->values()
                    ->all();
            }
        );

        $categories = Cache::remember(
            ProductCategory::HOME_FEATURED_CACHE_KEY,
            now()->addMinutes(15),
            function () {
                return ProductCategory::query()
                    ->active()
                    ->orderBy('position')
                    ->take(6)
                    ->get()
                    ->map(fn (ProductCategory $c) => [
                        'id'          => $c->id,
                        'name'        => $c->name,
                        'slug'        => $c->slug,
                        'description' => $c->description,
                        'icon'        => $c->icon ?? 'bi bi-droplet',
                        'accent'      => $c->accent,
                    ])
                    ->values()
                    ->all();
            }
        );

        return Inertia::render('Home/Index', [
            'featuredProducts'   => $products,
            'featuredCategories' => $categories,
        ]);
    }
}
