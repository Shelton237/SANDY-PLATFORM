<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company() . ' Farms',
            'contact_name' => fake()->name(),
            'email' => fake()->companyEmail(),
            'phone' => fake()->phoneNumber(),
            'city' => fake()->city(),
            'country' => 'France',
            'lead_time_days' => fake()->numberBetween(1, 5),
            'reliability_score' => fake()->numberBetween(70, 99),
            'expertise' => fake()->randomElement(['Agrumes', 'Légumes verts', 'Superfoods']),
            'status' => fake()->randomElement(['active', 'preferred', 'on_hold']),
            'certifications' => fake()->boolean(60) ? 'AB / Bio' : null,
            'notes' => fake()->sentence(),
            'catalog' => [
                'ingredients' => fake()->randomElements(['gingembre', 'kale', 'ananas', 'curcuma', 'betterave'], 3),
                'min_order' => fake()->numberBetween(20, 60),
            ],
        ];
    }
}
