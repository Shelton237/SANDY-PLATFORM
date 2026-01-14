<?php

namespace Database\Factories;

use App\Models\Delivery;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Delivery>
 */
class DeliveryFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(array_keys(Delivery::statuses()));
        $scheduled = fake()->dateTimeBetween('-2 days', '+5 days');

        return [
            'order_id' => Order::factory(),
            'status' => $status,
            'scheduled_date' => $scheduled->format('Y-m-d'),
            'time_window' => fake()->randomElement(['8h-10h', '10h-12h', '12h-14h', '18h-20h']),
            'courier_name' => fake()->name(),
            'courier_phone' => fake()->phoneNumber(),
            'vehicle_type' => fake()->randomElement(['bike', 'scooter', 'van']),
            'tracking_link' => fake()->boolean(30) ? fake()->url() : null,
            'dispatched_at' => $status === Delivery::STATUS_DISPATCHED ? now() : null,
            'delivered_at' => $status === Delivery::STATUS_DELIVERED ? now() : null,
            'notes' => fake()->boolean(20) ? fake()->sentence() : null,
            'address_line1' => fake()->streetAddress(),
            'address_line2' => null,
            'postal_code' => fake()->postcode(),
            'city' => fake()->city(),
            'instructions' => fake()->boolean(30) ? fake()->sentence(6) : null,
        ];
    }
}
