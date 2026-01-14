<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    private const SORTS = [
        'popularity' => 'Coups de coeur',
        'price_asc' => 'Prix croissant',
        'price_desc' => 'Prix decroissant',
        'energy' => 'Boost energie',
        'calories_low' => 'Calories basees'
    ];

    public function index(Request $request): Response
    {
        $filters = $this->buildFilters($request);
        $catalog = $this->catalog();
        $juices = $this->applyFilters($catalog, $filters);

        return $this->renderCatalogue($catalog, $juices, $filters);
    }

    public function byCategory(Request $request, string $category): Response
    {
        $catalog = $this->catalog();
        $categories = $this->categories();
        $categoryMeta = $categories->firstWhere('slug', $category);

        if (!$categoryMeta) {
            $hasProducts = $catalog->contains(fn ($product) => $product['category'] === $category);
            if (!$hasProducts) {
                abort(404);
            }

            $categoryMeta = [
                'slug' => $category,
                'name' => Str::of($category)->replace('-', ' ')->title(),
                'description' => null,
                'icon' => null,
                'accent' => null,
                'tagline' => null,
            ];
        }

        $filters = $this->buildFilters($request, $category);
        $filters['category'] = $category;

        $juices = $this->applyFilters($catalog, $filters);

        return $this->renderCatalogue($catalog, $juices, $filters, $categoryMeta);
    }

    public function show(string $slug): Response
    {
        $catalog = $this->catalog();
        $product = $catalog->firstWhere('slug', $slug);

        if (!$product) {
            abort(404);
        }

        $category = $this->categories()->firstWhere('slug', $product['category']);
        $related = $catalog
            ->where('category', $product['category'])
            ->where('slug', '!=', $slug)
            ->take(3)
            ->values()
            ->all();

        return Inertia::render('Products/Show', [
            'product' => $product,
            'category' => $category,
            'related' => $related,
            'nutritionFocus' => config('juices.nutrition_focus', []),
            'moments' => config('juices.moments', [])
        ]);
    }

    private function renderCatalogue(Collection $catalog, Collection $juices, array $filters, ?array $categoryContext = null): Response
    {
        $categories = $this->categories();
        $metrics = [
            'recipes' => $catalog->count(),
            'categories' => $categories->count(),
            'limited' => $catalog->where('is_limited', true)->count(),
            'averageCalories' => (int) round($catalog->avg('calories')),
        ];

        return Inertia::render('Products/Catalogue', [
            'juices' => $juices->values()->all(),
            'categories' => $categories->values()->all(),
            'collections' => $this->formatCollections($catalog),
            'nutritionFocus' => config('juices.nutrition_focus', []),
            'filters' => $filters,
            'categoryContext' => $categoryContext,
            'featured' => $this->formatFeatured($catalog),
            'metrics' => $metrics,
            'moments' => config('juices.moments', []),
            'sortOptions' => $this->sortOptions(),
        ]);
    }

    private function buildFilters(Request $request, ?string $defaultCategory = null): array
    {
        $search = trim((string) $request->input('q', ''));
        $category = $request->input('category');

        if ($category === null || $category === '') {
            $category = $defaultCategory;
        }

        $moment = $request->input('moment');
        if ($moment === '') {
            $moment = null;
        }

        $sort = $request->input('sort', 'popularity');
        if (!array_key_exists($sort, self::SORTS)) {
            $sort = 'popularity';
        }

        return [
            'search' => $search,
            'category' => $category,
            'moment' => $moment,
            'sort' => $sort
        ];
    }

    private function applyFilters(Collection $catalog, array $filters): Collection
    {
        return $catalog
            ->when($filters['category'], fn ($items) => $items->where('category', $filters['category']))
            ->when($filters['moment'], fn ($items) => $items->filter(fn ($product) => in_array($filters['moment'], $product['moments'] ?? [], true)))
            ->when($filters['search'], function ($items) use ($filters) {
                $search = Str::of($filters['search'])->lower();

                return $items->filter(function ($product) use ($search) {
                    $haystack = Str::of(
                        ($product['name'] ?? '') . ' ' .
                        ($product['tagline'] ?? '') . ' ' .
                        ($product['description'] ?? '')
                    )->lower();

                    if ($haystack->contains($search)) {
                        return true;
                    }

                    $ingredients = collect($product['ingredients'] ?? [])
                        ->map(function ($ingredient) {
                            if (is_array($ingredient)) {
                                return $ingredient['name'] ?? '';
                            }
                            return $ingredient;
                        })
                        ->implode(' ');

                    return Str::of($ingredients)->lower()->contains($search);
                });
            })
            ->when($filters['sort'], function ($items) use ($filters) {
                return match ($filters['sort']) {
                    'price_asc' => $items->sortBy('price'),
                    'price_desc' => $items->sortByDesc('price'),
                    'energy' => $items->sortByDesc('energy_index'),
                    'calories_low' => $items->sortBy('calories'),
                    default => $items->sortByDesc('popularity'),
                };
            })
            ->values();
    }

    private function catalog(): Collection
    {
        return Product::query()
            ->published()
            ->with(['images' => fn ($query) => $query->orderBy('position')->orderBy('id')])
            ->withCount('orderItems as orders_count')
            ->latest('created_at')
            ->get()
            ->map(fn (Product $product) => $this->transformProduct($product));
    }

    private function categories(): Collection
    {
        return ProductCategory::query()
            ->active()
            ->orderBy('position')
            ->orderBy('name')
            ->get()
            ->map(function (ProductCategory $category) {
                return [
                    'id' => $category->id,
                    'slug' => $category->slug,
                    'name' => $category->name,
                    'description' => $category->description,
                    'icon' => $category->icon,
                    'accent' => $category->accent,
                    'tagline' => $category->tagline,
                ];
            });
    }

    private function formatCollections(Collection $catalog): array
    {
        $map = $catalog->keyBy('slug');

        return collect(config('juices.collections', []))
            ->map(function ($collection) use ($map) {
                $collection['items'] = collect($collection['products'] ?? [])
                    ->map(fn ($slug) => $map->get($slug))
                    ->filter()
                    ->values()
                    ->all();

                return $collection;
            })
            ->filter(fn ($collection) => !empty($collection['items']))
            ->values()
            ->all();
    }

    private function formatFeatured(Collection $catalog): array
    {
        $hero = $catalog->firstWhere('is_new', true) ?? $catalog->first();
        $new = $catalog->filter(fn ($product) => $product['is_new'])->first();
        $limited = $catalog->filter(fn ($product) => $product['is_limited'])->first();

        return [
            'hero' => $hero,
            'new' => $new,
            'limited' => $limited,
        ];
    }

    private function sortOptions(): array
    {
        return collect(self::SORTS)
            ->map(fn ($label, $value) => [
                'value' => $value,
                'label' => $label,
            ])
            ->values()
            ->all();
    }

    private function transformProduct(Product $product): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'category' => $product->category,
            'tagline' => $product->tagline,
            'description' => $product->description,
            'price' => (float) $product->price,
            'size' => $product->size,
            'availability' => $product->availability,
            'stock' => $product->stock,
            'status' => $product->status,
            'is_limited' => $product->is_limited,
            'is_new' => $product->is_new,
            'energy_index' => $product->energy_index,
            'calories' => $product->calories,
            'sugars' => $product->sugars,
            'fibers' => $product->fibers,
            'ingredients' => $this->normalizeIngredients($product->ingredients),
            'benefits' => $product->benefits ?? [],
            'moments' => $product->moments ?? [],
            'taste' => $product->taste_notes ?? [],
            'badge' => $product->badge,
            'accent' => $product->accent,
            'image' => $product->image_path,
            'images' => $product->images->map(fn ($image) => [
                'id' => $image->id,
                'url' => $image->url,
                'alt' => $image->alt,
                'position' => $image->position,
            ])->values()->all(),
            'season' => data_get($product->metadata, 'season'),
            'vitamins' => data_get($product->metadata, 'vitamins', []),
            'metadata' => $product->metadata ?? [],
            'created_at' => $product->created_at,
            'popularity' => $product->orders_count ?? 0,
        ];
    }

    private function normalizeIngredients(?array $ingredients): array
    {
        return collect($ingredients ?? [])
            ->map(function ($ingredient) {
                if (is_array($ingredient)) {
                    return [
                        'name' => $ingredient['name'] ?? '',
                        'percentage' => $ingredient['percentage'] ?? null,
                    ];
                }

                $value = trim((string) $ingredient);
                if (str_contains($value, ':')) {
                    [$name, $percentage] = array_map('trim', explode(':', $value, 2));
                    return [
                        'name' => $name,
                        'percentage' => is_numeric($percentage) ? (float) $percentage : null,
                    ];
                }

                return [
                    'name' => $value,
                    'percentage' => null,
                ];
            })
            ->filter(fn ($ingredient) => !empty($ingredient['name']))
            ->values()
            ->all();
    }
}
