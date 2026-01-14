<?php

namespace App\Support\Cart;

use App\Models\Product;
use Illuminate\Contracts\Session\Session;

class CartManager
{
    public const SESSION_KEY = 'cart.items';

    public function __construct(
        private readonly Session $session
    ) {
    }

    public function items(): array
    {
        return collect($this->session->get(self::SESSION_KEY, []))
            ->map(function (array $item) {
                $quantity = max(1, (int) ($item['quantity'] ?? 1));
                $unitPrice = (float) ($item['price'] ?? 0);

                return [
                    ...$item,
                    'quantity' => $quantity,
                    'price' => $unitPrice,
                    'total' => $unitPrice * $quantity,
                ];
            })
            ->values()
            ->all();
    }

    public function count(): int
    {
        return collect($this->items())->sum('quantity');
    }

    public function isEmpty(): bool
    {
        return empty($this->session->get(self::SESSION_KEY, []));
    }

    public function add(Product $product, int $quantity = 1): void
    {
        $cart = $this->session->get(self::SESSION_KEY, []);
        $key = (string) $product->id;

        $quantity = max(1, min($quantity, $this->maxQuantity()));
        $base = $cart[$key] ?? $this->snapshot($product);

        $base['quantity'] = max(
            1,
            min(($base['quantity'] ?? 0) + $quantity, $this->maxQuantity())
        );
        $base['price'] = (float) $product->price;

        $cart[$key] = $base;
        $this->session->put(self::SESSION_KEY, $cart);
    }

    public function update(Product $product, int $quantity): void
    {
        $cart = $this->session->get(self::SESSION_KEY, []);
        $key = (string) $product->id;

        if (!array_key_exists($key, $cart)) {
            return;
        }

        $quantity = max(1, min($quantity, $this->maxQuantity()));

        $cart[$key]['quantity'] = $quantity;
        $cart[$key]['price'] = (float) $product->price;
        $cart[$key]['name'] = $product->name;

        $this->session->put(self::SESSION_KEY, $cart);
    }

    public function remove(Product $product): void
    {
        $cart = $this->session->get(self::SESSION_KEY, []);
        $key = (string) $product->id;

        unset($cart[$key]);
        $this->session->put(self::SESSION_KEY, $cart);
    }

    public function clear(): void
    {
        $this->session->forget(self::SESSION_KEY);
    }

    public function totals(string $deliveryType = 'delivery'): array
    {
        $items = $this->items();
        $subtotal = round(collect($items)->sum('total'), 2);
        $deliveryFee = $this->computeDeliveryFee($subtotal, $deliveryType);

        return [
            'currency' => config('shop.currency', 'FCFA'),
            'items' => $items,
            'count' => collect($items)->sum('quantity'),
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'total' => $subtotal + $deliveryFee,
            'free_delivery_threshold' => (float) config('shop.free_delivery_threshold', 0),
        ];
    }

    private function computeDeliveryFee(float $subtotal, string $deliveryType): float
    {
        if ($deliveryType === 'pickup') {
            return 0;
        }

        $baseFee = (float) config('shop.delivery_fee', 0);
        $threshold = (float) config('shop.free_delivery_threshold', 0);

        return $threshold > 0 && $subtotal >= $threshold ? 0 : $baseFee;
    }

    private function snapshot(Product $product): array
    {
        $primaryImage = $product->images()->orderBy('position')->orderBy('id')->value('url');

        return [
            'product_id' => $product->id,
            'slug' => $product->slug,
            'name' => $product->name,
            'price' => (float) $product->price,
            'image' => $primaryImage ?: $product->image_path,
            'size' => $product->size,
            'accent' => $product->accent,
            'quantity' => 0,
        ];
    }

    private function maxQuantity(): int
    {
        return max(1, (int) config('shop.max_quantity', 12));
    }
}
