<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(Request $request): Response
    {
        $filters = $request->only('search', 'status', 'category');

        $products = Product::query()
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('tagline', 'like', "%{$search}%");
                });
            })
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->when($filters['category'] ?? null, fn ($query, $category) => $query->where('category', $category))
            ->withCount('orderItems as orders_count')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $stats = [
            'total' => Product::count(),
            'published' => Product::where('status', Product::STATUS_PUBLISHED)->count(),
            'drafts' => Product::where('status', Product::STATUS_DRAFT)->count(),
            'limited' => Product::where('is_limited', true)->count(),
        ];

        $categories = ProductCategory::query()
            ->orderBy('position')
            ->orderBy('name')
            ->get(['slug', 'name']);

        return Inertia::render('Admin/Products/Index', [
            'products' => $products,
            'filters' => $filters,
            'stats' => $stats,
            'categories' => $categories,
            'statuses' => Product::statuses(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Products/Create', [
            'product' => $this->blankProduct(),
            'statuses' => Product::statuses(),
            'categories' => $this->categoryOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateProduct($request);

        $product = Product::create($data);
        $this->syncImages($product, $request->input('images', []));

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', 'Produit ajouté avec succès.');
    }

    public function show(Product $product): Response
    {
        $product->load(['images' => fn ($query) => $query->orderBy('position')->orderBy('id')])
            ->loadCount('orderItems');

        return Inertia::render('Admin/Products/Show', [
            'product' => $product,
            'statuses' => Product::statuses(),
            'categories' => $this->categoryOptions(),
        ]);
    }

    public function edit(Product $product): Response
    {
        $product->load(['images' => fn ($query) => $query->orderBy('position')->orderBy('id')]);

        return Inertia::render('Admin/Products/Edit', [
            'product' => $product,
            'statuses' => Product::statuses(),
            'categories' => $this->categoryOptions(),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validateProduct($request, $product);
        $product->update($data);
        $this->syncImages($product, $request->input('images', []));

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', 'Produit mis à jour.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produit supprimé.');
    }

    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'max:4096'],
        ]);

        $path = $request->file('image')->store('products', 'public');

        return response()->json([
            'url' => Storage::disk('public')->url($path),
            'path' => $path,
        ]);
    }

    private function blankProduct(): array
    {
        return [
            'name' => '',
            'slug' => '',
            'category' => $this->categoryOptions()->first()['slug'] ?? '',
            'tagline' => '',
            'description' => '',
            'price' => 0,
            'size' => '',
            'availability' => '',
            'stock' => 0,
            'status' => Product::STATUS_DRAFT,
            'is_limited' => false,
            'is_new' => false,
            'energy_index' => 0,
            'calories' => null,
            'sugars' => null,
            'fibers' => null,
            'ingredients' => [],
            'benefits' => [],
            'moments' => [],
            'taste_notes' => [],
            'badge' => '',
            'accent' => '',
            'image_path' => '',
            'batch_note' => '',
            'metadata' => [],
            'images' => [],
        ];
    }

    private function validateProduct(Request $request, ?Product $product = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($product?->id)],
            'category' => ['required', 'string', 'max:120', Rule::exists('product_categories', 'slug')],
            'tagline' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'size' => ['nullable', 'string', 'max:120'],
            'availability' => ['nullable', 'string', 'max:255'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'string', 'in:' . implode(',', array_keys(Product::statuses()))],
            'is_limited' => ['boolean'],
            'is_new' => ['boolean'],
            'energy_index' => ['nullable', 'integer', 'min:0', 'max:10'],
            'calories' => ['nullable', 'integer', 'min:0'],
            'sugars' => ['nullable', 'integer', 'min:0'],
            'fibers' => ['nullable', 'numeric', 'min:0'],
            'ingredients' => ['nullable'],
            'benefits' => ['nullable'],
            'moments' => ['nullable'],
            'taste_notes' => ['nullable'],
            'badge' => ['nullable', 'string', 'max:120'],
            'accent' => ['nullable', 'string', 'max:120'],
            'image_path' => ['nullable', 'string', 'max:255'],
            'batch_note' => ['nullable', 'string', 'max:255'],
            'metadata' => ['nullable'],
        ]);

        $data['slug'] = Str::slug($data['slug'] ?? $data['name']);
        $data['stock'] = $data['stock'] ?? 0;
        $data['ingredients'] = $this->normalizeList($data['ingredients'] ?? []);
        $data['benefits'] = $this->normalizeList($data['benefits'] ?? []);
        $data['moments'] = $this->normalizeList($data['moments'] ?? []);
        $data['taste_notes'] = $this->normalizeList($data['taste_notes'] ?? []);

        if (!empty($data['metadata']) && is_string($data['metadata'])) {
            $decoded = json_decode($data['metadata'], true);
            $data['metadata'] = json_last_error() === JSON_ERROR_NONE ? $decoded : [];
        }

        return $data;
    }

    private function normalizeList($value): array
    {
        if (is_array($value)) {
            $containsStructuredItems = collect($value)->contains(fn ($item) => is_array($item));

            if ($containsStructuredItems) {
                return array_values(array_filter($value, fn ($item) => !empty($item)));
            }

            $items = $value;
        } elseif (is_string($value)) {
            $items = preg_split('/\r\n|[\r\n]/', $value);
        } else {
            $items = [];
        }

        return array_values(array_filter(
            array_map(fn ($item) => trim((string) $item), $items),
            fn ($item) => $item !== ''
        ));
    }

    private function categoryOptions()
    {
        return ProductCategory::query()
            ->orderBy('position')
            ->orderBy('name')
            ->get(['slug', 'name', 'accent']);
    }

    private function syncImages(Product $product, array $images = []): void
    {
        $payload = collect($images)
            ->map(function ($image, $index) {
                $url = trim((string) ($image['url'] ?? ''));

                if ($url === '') {
                    return null;
                }

                return [
                    'url' => $url,
                    'alt' => trim((string) ($image['alt'] ?? '')),
                    'position' => is_numeric($image['position'] ?? null) ? (int) $image['position'] : $index,
                ];
            })
            ->filter()
            ->values()
            ->all();

        $product->images()->delete();

        if (!empty($payload)) {
            $product->images()->createMany($payload);
        }
    }
}
