<?php

namespace App\Http\Controllers;

use App\Models\LandingSection;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $heroContent = Cache::remember(
            LandingSection::HERO_CACHE_KEY,
            now()->addMinutes(15),
            fn () => LandingSection::key('home_hero')->first()?->content ?? []
        );

        $products = collect(Cache::remember(
            Product::HOME_FEATURED_CACHE_KEY,
            now()->addMinutes(5),
            function () {
                return Product::query()
                    ->published()
                    ->with(['images' => fn ($query) => $query->orderBy('position')->orderBy('id')])
                    ->latest('created_at')
                    ->take(4)
                    ->get()
                    ->map(fn (Product $product) => [
                        'id' => $product->id,
                        'name' => $product->name,
                        'slug' => $product->slug,
                        'tagline' => $product->tagline,
                        'price' => (float) $product->price,
                        'is_new' => $product->is_new,
                        'image' => $product->images->first()?->url ?? $product->image_path,
                        'accent' => $product->accent,
                    ])
                    ->values()
                    ->all();
            }
        ));

        $categories = collect(Cache::remember(
            ProductCategory::HOME_FEATURED_CACHE_KEY,
            now()->addMinutes(15),
            function () {
                return ProductCategory::query()
                    ->active()
                    ->orderBy('position')
                    ->take(3)
                    ->get()
                    ->map(fn (ProductCategory $category) => [
                        'id' => $category->id,
                        'name' => $category->name,
                        'slug' => $category->slug,
                        'description' => $category->description,
                        'accent' => $category->accent,
                    ])
                    ->values()
                    ->all();
            }
        ));

        return Inertia::render('Home/Index', [
            'hero' => $heroContent,
            'featuredProducts' => $products->values()->all(),
            'featuredCategories' => $categories->values()->all(),
        ]);
    }
}
