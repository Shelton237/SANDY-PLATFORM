<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SupplyOrder>
 */
class SupplyOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $ingredient = fake()->randomElement(['Gingembre', 'Kale', 'Ananas', 'Citron', 'Menthe']);
        $unit = fake()->randomElement(['kg', 'L']);
        $quantity = fake()->randomFloat(2, 20, 120);

        return [
            'supplier_id' => Supplier::factory(),
            'reference' => 'PO-' . Str::upper(Str::random(6)),
            'ingredient' => $ingredient,
            'quantity' => $quantity,
            'unit' => $unit,
            'status' => fake()->randomElement(['ordered', 'in_transit', 'received', 'quality_check']),
            'transport_mode' => fake()->randomElement(['courier', 'truck', 'cargo']),
            'storage_location' => fake()->randomElement(['Chambre Froide A', 'Cellier', 'Atelier']),
            'quality_status' => fake()->randomElement(['pending', 'valid', 'rework']),
            'ordered_at' => fake()->dateTimeBetween('-10 days', '-2 days'),
            'expected_at' => fake()->dateTimeBetween('now', '+5 days'),
            'received_at' => fake()->boolean(50) ? fake()->dateTimeBetween('-1 day', 'now') : null,
            'total_cost' => round($quantity * fake()->randomFloat(2, 2, 8), 2),
            'metadata' => [
                'humidity' => fake()->numberBetween(40, 65),
                'temperature' => fake()->numberBetween(2, 6),
            ],
        ];
    }
}
