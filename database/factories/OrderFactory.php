<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(array_keys(Order::statuses()));
        $payment = fake()->randomElement(array_keys(Order::paymentStatuses()));
        $paymentMethod = fake()->randomElement(array_keys(Order::paymentMethods()));
        $placedAt = fake()->dateTimeBetween('-2 weeks', 'now');
        $confirmedAt = in_array($status, [
            Order::STATUS_CONFIRMED,
            Order::STATUS_IN_PRODUCTION,
            Order::STATUS_READY,
            Order::STATUS_COMPLETED,
        ], true) ? fake()->dateTimeBetween($placedAt, 'now') : null;
        $completedAt = $status === Order::STATUS_COMPLETED
            ? fake()->dateTimeBetween($confirmedAt ?? $placedAt, 'now')
            : null;

        return [
            'user_id' => fake()->boolean(40) ? User::factory() : null,
            'number' => 'SJ-' . Str::upper(Str::random(5)),
            'status' => $status,
            'payment_status' => $payment,
            'payment_method' => $paymentMethod,
            'delivery_status' => fake()->randomElement(['pending', 'scheduled', 'dispatched', 'delivered']),
            'delivery_type' => fake()->randomElement(['delivery', 'pickup']),
            'customer_name' => fake()->name(),
            'customer_email' => fake()->unique()->safeEmail(),
            'customer_phone' => fake()->phoneNumber(),
            'address_line1' => fake()->streetAddress(),
            'address_line2' => null,
            'postal_code' => fake()->postcode(),
            'city' => fake()->city(),
            'country' => 'France',
            'notes' => fake()->boolean(30) ? fake()->sentence() : null,
            'subtotal' => 0,
            'delivery_fee' => fake()->randomElement([0, 3.90, 4.90]),
            'total' => 0,
            'placed_at' => $placedAt,
            'confirmed_at' => $confirmedAt,
            'completed_at' => $completedAt,
        ];
    }
}
