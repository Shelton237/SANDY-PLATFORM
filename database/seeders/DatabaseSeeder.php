<?php

namespace Database\Seeders;

use App\Models\Delivery;
use App\Models\InventoryBatch;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductionBatch;
use App\Models\Supplier;
use App\Models\SupplyOrder;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@sandy.test'],
            [
                'name' => 'Sandy Admin',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'is_admin' => true,
            ],
        );

        $customers = User::factory()->count(5)->create();

        $this->call([
            ProductCategorySeeder::class,
            ProductSeeder::class,
            HomeContentSeeder::class,
            BlogPostSeeder::class,
        ]);

        $suppliers = Supplier::factory()->count(4)->create();

        $products = Product::all();

        Product::doesntHave('images')->each(function (Product $product) {
            ProductImage::factory()->count(fake()->numberBetween(1, 3))->for($product)->sequence(
                fn ($sequence) => ['position' => $sequence->index]
            )->create();
        });

        $supplyOrders = $suppliers->flatMap(fn ($supplier) => SupplyOrder::factory()->count(3)->for($supplier)->create());

        $suppliers->each(function (Supplier $supplier) {
            InventoryBatch::factory()->count(2)->for($supplier)->create();
        });

        Order::factory()
            ->count(20)
            ->create()
            ->each(function (Order $order) use ($products, $customers) {
                if (fake()->boolean(60)) {
                    $order->user_id = $customers->random()->id;
                    $order->save();
                }

                $items = $this->generateItemsPayload($products);
                $order->items()->createMany($items);

                $subtotal = $order->items->sum('total_price');
                $deliveryFee = $order->delivery_fee ?? 0;
                $order->update([
                    'subtotal' => $subtotal,
                    'total' => $subtotal + $deliveryFee,
                ]);

                $deliveryStatus = fake()->randomElement(array_keys(Delivery::statuses()));
                Delivery::factory()
                    ->for($order)
                    ->state([
                        'status' => $deliveryStatus,
                        'scheduled_date' => fake()->dateTimeBetween('now', '+5 days')->format('Y-m-d'),
                        'time_window' => fake()->randomElement(['8h-10h', '10h-12h', '12h-14h', '18h-20h']),
                        'courier_name' => fake()->name(),
                        'courier_phone' => fake()->phoneNumber(),
                    ])
                    ->create();

                $order->update(['delivery_status' => $deliveryStatus]);
            });

        $products->each(function (Product $product) {
            ProductionBatch::factory()
                ->count(2)
                ->for($product)
                ->create();
        });

        $this->call(FinanceSeeder::class);
    }

    private function generateItemsPayload(Collection $products): array
    {
        $count = fake()->numberBetween(2, 4);

        return collect(range(1, $count))->map(function () use ($products) {
            $product = $products->random();
            $quantity = fake()->numberBetween(1, 3);
            $unitPrice = $product->price;

            return [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_price' => round($unitPrice * $quantity, 2),
                'metadata' => [
                    'category' => $product->category,
                    'accent' => $product->accent,
                ],
            ];
        })->toArray();
    }
}
