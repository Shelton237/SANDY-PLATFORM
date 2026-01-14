<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductionBatch>
 */
class ProductionBatchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $planned = fake()->randomFloat(2, 120, 280);
        $status = fake()->randomElement(['planned', 'in_progress', 'quality_check', 'packaged']);

        return [
            'product_id' => Product::factory(),
            'inventory_batch_id' => null,
            'code' => 'PB-' . Str::upper(Str::random(6)),
            'status' => $status,
            'planned_liters' => $planned,
            'actual_liters' => $status === 'packaged' ? $planned - fake()->randomFloat(2, 2, 12) : null,
            'yield_percent' => $status === 'packaged' ? fake()->numberBetween(85, 98) : null,
            'team_lead' => fake()->name(),
            'shift' => fake()->randomElement(['matin', 'aprem', 'soir']),
            'starts_at' => fake()->dateTimeBetween('-3 days', 'now'),
            'ends_at' => $status === 'packaged' ? fake()->dateTimeBetween('-1 day', 'now') : null,
            'notes' => fake()->sentence(),
            'ingredients_used' => [
                ['name' => 'Gingembre', 'kg' => fake()->randomFloat(1, 5, 15)],
                ['name' => 'Pomme', 'kg' => fake()->randomFloat(1, 15, 40)],
            ],
            'quality_checks' => [
                ['type' => 'PH', 'value' => fake()->randomFloat(1, 3.2, 3.8)],
                ['type' => 'Brix', 'value' => fake()->randomFloat(1, 10, 13)],
            ],
        ];
    }
}
