<?php

namespace Database\Factories;

use App\Models\InventoryBatch;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InventoryBatch>
 */
class InventoryBatchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $ingredient = fake()->randomElement(['Gingembre', 'Pomme', 'Kale', 'Citron Vert', 'Menthe']);
        $quantity = fake()->randomFloat(2, 10, 80);

        return [
            'supplier_id' => Supplier::factory(),
            'batch_code' => 'LOT-' . Str::upper(Str::random(5)),
            'ingredient' => $ingredient,
            'quantity_available' => $quantity,
            'unit' => 'kg',
            'status' => fake()->randomElement(['available', 'reserved', 'low', 'quality_hold']),
            'location' => fake()->randomElement(['Chambre Froide A', 'Chambre Froide B', 'Séchoir']),
            'reserved_for' => fake()->boolean(40) ? fake()->randomElement(['Batch Detox', 'Kids Pack']) : null,
            'received_at' => fake()->dateTimeBetween('-7 days', '-1 day'),
            'expires_at' => fake()->dateTimeBetween('+3 days', '+15 days'),
            'metadata' => [
                'brix' => fake()->randomFloat(1, 7, 12),
            ],
        ];
    }
}
