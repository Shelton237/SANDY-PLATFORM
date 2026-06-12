<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\PushService;

class ProductObserver
{
    public function __construct(private readonly PushService $push) {}

    public function created(Product $product): void
    {
        if ($product->is_published) {
            $this->notify($product);
        }
    }

    public function updated(Product $product): void
    {
        // Déclenche uniquement quand le produit passe en "publié"
        if ($product->isDirty('is_published') && $product->is_published) {
            $this->notify($product);
        }
    }

    private function notify(Product $product): void
    {
        $price = $product->price
            ? ' — ' . number_format((float) $product->price, 0, ',', ' ') . ' FCFA'
            : '';

        $this->push->broadcast(
            title: '🥤 Nouveau produit disponible !',
            body:  $product->name . $price,
            url:   '/products/' . $product->slug,
            image: $product->image ?? null,
        );
    }
}
