<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class ProductCategoryController extends Controller
{
    public function index(): Response
    {
        $categories = ProductCategory::query()
            ->orderBy('position')
            ->orderBy('name')
            ->get();

        return Inertia::render('Admin/Products/Categories', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateCategory($request);
        $data['position'] = $data['position'] ?? ((ProductCategory::max('position') ?? 0) + 1);

        ProductCategory::create($data);

        return back()->with('success', 'Catégorie ajoutée.');
    }

    public function update(ProductCategory $productCategory, Request $request): RedirectResponse
    {
        $data = $this->validateCategory($request, $productCategory);
        $productCategory->update($data);

        return back()->with('success', 'Catégorie mise à jour.');
    }

    public function destroy(ProductCategory $productCategory): RedirectResponse
    {
        if ($productCategory->products()->exists()) {
            return back()->with('error', 'Impossible de supprimer une catégorie utilisée par des produits.');
        }

        $productCategory->delete();

        return back()->with('success', 'Catégorie supprimée.');
    }

    private function validateCategory(Request $request, ?ProductCategory $category = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:120', Rule::unique('product_categories', 'slug')->ignore($category?->id)],
            'tagline' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:120'],
            'accent' => ['nullable', 'string', 'max:60'],
            'hero_image' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'position' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['is_featured'] = $request->boolean('is_featured', false);

        return $data;
    }
}
