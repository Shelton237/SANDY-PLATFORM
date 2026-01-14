<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Support\Cart\CartManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function __construct(
        private readonly CartManager $cart
    ) {
    }

    public function index(): Response
    {
        return Inertia::render('Cart/Index', [
            'cart' => $this->cart->totals(),
        ]);
    }

    public function add(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:' . config('shop.max_quantity', 12)],
        ]);

        $this->cart->add($product, (int) ($data['quantity'] ?? 1));

        return back()->with('success', "{$product->name} a été ajouté à votre panier.");
    }

    public function update(Request $request, Product $cartItem): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:' . config('shop.max_quantity', 12)],
        ]);

        $this->cart->update($cartItem, (int) $data['quantity']);

        return back()->with('success', 'Quantité mise à jour.');
    }

    public function remove(Product $cartItem): RedirectResponse
    {
        $this->cart->remove($cartItem);

        return back()->with('success', 'Produit retiré du panier.');
    }

    public function clear(): RedirectResponse
    {
        $this->cart->clear();

        return redirect()->route('products')->with('success', 'Votre panier a été vidé.');
    }
}
