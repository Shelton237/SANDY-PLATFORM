<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = config('juices.categories', []);

        foreach ($categories as $index => $category) {
            ProductCategory::updateOrCreate(
                ['slug' => Arr::get($category, 'slug', 'category-' . $index)],
                [
                    'name' => $category['name'] ?? 'Categorie',
                    'description' => $category['description'] ?? null,
                    'tagline' => $category['tagline'] ?? null,
                    'icon' => $category['icon'] ?? null,
                    'accent' => $category['accent'] ?? null,
                    'hero_image' => $category['hero_image'] ?? null,
                    'is_active' => true,
                    'is_featured' => $category['is_featured'] ?? false,
                    'position' => $index + 1,
                    'metadata' => Arr::only($category, ['nutrition', 'cta']),
                ]
            );
        }
    }
}
