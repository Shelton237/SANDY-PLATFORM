<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function configure()
    {
        return $this->afterCreating(function (Product $product) {
            ProductImage::factory()
                ->count(fake()->numberBetween(1, 3))
                ->sequence(fn ($sequence) => ['position' => $sequence->index])
                ->for($product)
                ->create();
        });
    }

    public function definition(): array
    {
        $categories = ProductCategory::query()->select(['slug', 'name', 'accent'])->get();
        if ($categories->isEmpty()) {
            $categories = collect([
                ['slug' => 'detox', 'name' => 'Detox', 'accent' => 'emerald'],
                ['slug' => 'energy', 'name' => 'Energy', 'accent' => 'orange'],
                ['slug' => 'beauty', 'name' => 'Beauty', 'accent' => 'rose'],
                ['slug' => 'kids', 'name' => 'Kids', 'accent' => 'amber'],
                ['slug' => 'calm', 'name' => 'Calm', 'accent' => 'indigo'],
            ]);
        }

        $category = $categories->random();
        $base = fake()->unique()->words(2, true);
        $name = Str::title($base . ' ' . $category['name']);

        $ingredients = collect(range(1, 4))->map(function () {
            return [
                'name' => Str::title(fake()->word()),
                'percentage' => fake()->numberBetween(10, 40),
            ];
        })->toArray();

        $moments = ['morning', 'afternoon', 'sport', 'evening', 'kids'];

        $vitamins = collect(['Vit C', 'Vit A', 'Vit K', 'Potassium', 'Magnesium'])
            ->random(2)
            ->map(function ($label) {
                return [
                    'label' => $label,
                    'value' => fake()->numberBetween(20, 150) . ' % AJR',
                ];
            })
            ->values()
            ->all();

        return [
            'name' => $name,
            'slug' => Str::slug($name . '-' . fake()->unique()->numberBetween(100, 999)),
            'category' => $category['slug'],
            'tagline' => fake()->sentence(6),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 3, 9),
            'size' => fake()->randomElement(['250 ml', '330 ml', '500 ml']),
            'availability' => fake()->randomElement(['Pressage quotidien', 'Week-end', 'Edition limitée']),
            'stock' => fake()->numberBetween(10, 150),
            'status' => fake()->randomElement(array_keys(Product::statuses())),
            'is_limited' => fake()->boolean(20),
            'is_new' => fake()->boolean(30),
            'energy_index' => fake()->numberBetween(1, 5),
            'calories' => fake()->numberBetween(70, 150),
            'sugars' => fake()->numberBetween(8, 20),
            'fibers' => fake()->randomFloat(1, 1, 5),
            'ingredients' => $ingredients,
            'benefits' => fake()->sentences(3),
            'moments' => fake()->randomElements($moments, 2),
            'taste_notes' => fake()->randomElements(['tropical', 'epice', 'herbace', 'agrumes', 'floral'], 2),
            'badge' => fake()->randomElement(['signature', 'best seller', 'immunite', null]),
            'accent' => $category['accent'],
            'image_path' => null,
            'batch_note' => fake()->randomElement(['6h-11h', 'Weekend seulement', 'Pressage nocturne']),
            'metadata' => [
                'season' => fake()->randomElement(['all-year', 'summer', 'winter']),
                'vitamins' => $vitamins,
            ],
        ];
    }
}
