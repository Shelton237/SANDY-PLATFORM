<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $catalog = config('juices.catalog', []);

        if (empty($catalog)) {
            return;
        }

        foreach ($catalog as $item) {
            $ingredients = Arr::get($item, 'ingredients', []);
            $benefits = Arr::get($item, 'benefits', []);
            $moments = Arr::get($item, 'moments', []);
            $tasteNotes = Arr::get($item, 'taste', []);

            Product::updateOrCreate(
                ['slug' => $item['slug'] ?? Arr::get($item, 'name')],
                [
                    'name' => $item['name'] ?? 'Produit',
                    'category' => $item['category'] ?? 'detox',
                    'tagline' => $item['tagline'] ?? null,
                    'description' => $item['description'] ?? null,
                    'price' => $item['price'] ?? 0,
                    'size' => $item['size'] ?? null,
                    'availability' => $item['availability'] ?? null,
                    'stock' => Arr::get($item, 'stock', 150),
                    'status' => Arr::get($item, 'status', Product::STATUS_PUBLISHED),
                    'is_limited' => (bool) Arr::get($item, 'is_limited', false),
                    'is_new' => (bool) Arr::get($item, 'is_new', false),
                    'energy_index' => Arr::get($item, 'energy_index', 0),
                    'calories' => Arr::get($item, 'calories'),
                    'sugars' => Arr::get($item, 'sugars'),
                    'fibers' => Arr::get($item, 'fibers'),
                    'ingredients' => $ingredients,
                    'benefits' => $benefits,
                    'moments' => $moments,
                    'taste_notes' => $tasteNotes,
                    'badge' => $item['badge'] ?? null,
                    'accent' => $item['accent'] ?? null,
                    'image_path' => $item['image'] ?? null,
                    'batch_note' => $item['availability'] ?? null,
                    'metadata' => [
                        'season' => $item['season'] ?? null,
                        'popularity' => $item['popularity'] ?? null,
                        'vitamins' => $item['vitamins'] ?? [],
                        'collection' => $item['collection'] ?? null,
                    ],
                ]
            );
        }
    }
}
