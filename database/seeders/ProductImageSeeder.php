<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        Product::doesntHave('images')->each(function (Product $product) {
            ProductImage::factory()
                ->count(fake()->numberBetween(1, 3))
                ->sequence(fn ($sequence) => ['position' => $sequence->index])
                ->for($product)
                ->create();
        });
    }
}
