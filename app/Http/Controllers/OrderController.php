<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Order;
use App\Support\Cart\CartManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function __construct(
        private readonly CartManager $cart
    ) {
    }

    public function index(Request $request): Response
    {
        $orders = Order::query()
            ->where('user_id', $request->user()->id)
            ->latest('placed_at')
            ->select([
                'id',
                'number',
                'status',
                'payment_status',
                'payment_method',
                'delivery_status',
                'delivery_type',
                'total',
                'placed_at',
            ])
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Account/Orders/Index', [
            'orders' => $orders,
            'statusOptions' => Order::statuses(),
            'paymentStatuses' => Order::paymentStatuses(),
            'paymentMethods' => Order::paymentMethods(),
        ]);
    }

    public function show(Request $request, Order $order): Response
    {
        $this->authorizeOrder($request, $order);

        $order->load(['items.product', 'delivery']);

        return Inertia::render('Account/Orders/Show', [
            'order' => $order,
            'statusOptions' => Order::statuses(),
            'paymentStatuses' => Order::paymentStatuses(),
            'paymentMethods' => Order::paymentMethods(),
        ]);
    }

    public function checkout(Request $request): RedirectResponse|Response
    {
        if ($this->cart->isEmpty()) {
            return redirect()
                ->route('products')
                ->with('error', 'Ajoutez un produit à votre panier avant de poursuivre.');
        }

        $prefill = [
            'customer_name' => $request->user()?->name,
            'customer_email' => $request->user()?->email,
            'customer_phone' => $request->user()?->phone ?? null,
        ];

        return Inertia::render('Checkout/Index', [
            'cart' => $this->cart->totals(),
            'deliveryOptions' => $this->deliveryOptions(),
            'paymentOptions' => $this->paymentOptions(),
            'prefill' => array_filter($prefill),
        ]);
    }

    public function process(Request $request): RedirectResponse
    {
        if ($this->cart->isEmpty()) {
            return redirect()
                ->route('products')
                ->with('error', 'Votre panier est vide.');
        }

        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:40'],
            'address_line1' => ['nullable', 'string', 'max:255'],
            'address_line2' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:120'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'delivery_type' => ['required', Rule::in(['delivery', 'pickup'])],
            'payment_method' => ['required', Rule::in(array_keys($this->paymentOptions()))],
        ]);

        if ($data['delivery_type'] === 'delivery' && empty($data['address_line1'])) {
            return back()
                ->withErrors(['address_line1' => 'Merci de renseigner votre adresse de livraison.'])
                ->onlyInput(array_keys($data));
        }

        $cartTotals = $this->cart->totals($data['delivery_type']);

        if ($cartTotals['count'] < 1) {
            return redirect()
                ->route('products')
                ->with('error', 'Votre panier est vide.');
        }

        $order = DB::transaction(function () use ($data, $cartTotals, $request) {
            $user = $request->user();

            $order = Order::create([
                'user_id' => $user?->id,
                'status' => Order::STATUS_PENDING,
                'payment_status' => Order::PAYMENT_PENDING,
                'payment_method' => $data['payment_method'],
                'delivery_status' => Delivery::STATUS_PENDING,
                'delivery_type' => $data['delivery_type'],
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'],
                'address_line1' => $data['address_line1'] ?? null,
                'address_line2' => $data['address_line2'] ?? null,
                'city' => $data['city'] ?? null,
                'country' => 'Gabon',
                'notes' => $data['notes'] ?? null,
                'subtotal' => $cartTotals['subtotal'],
                'delivery_fee' => $cartTotals['delivery_fee'],
                'total' => $cartTotals['total'],
            ]);

            $itemsPayload = collect($cartTotals['items'])->map(function ($item) {
                return [
                    'product_id' => $item['product_id'],
                    'product_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['total'],
                    'metadata' => [
                        'slug' => $item['slug'],
                        'size' => $item['size'],
                    ],
                ];
            });

            $order->items()->createMany($itemsPayload->all());

            if ($data['delivery_type'] === 'delivery') {
                $order->delivery()->create([
                    'status' => Delivery::STATUS_PENDING,
                    'address_line1' => $data['address_line1'],
                    'address_line2' => $data['address_line2'] ?? null,
                    'city' => $data['city'] ?? null,
                    'notes' => $data['notes'] ?? null,
                ]);
            }

            return $order;
        });

        $this->cart->clear();
        $request->session()->put('checkout.order_id', $order->id);

        return redirect()
            ->route('checkout.success', $order)
            ->with('success', 'Commande enregistrée ! Nous vous appelons pour confirmer la livraison.');
    }

    public function success(Request $request, Order $order): Response
    {
        $this->authorizeOrder($request, $order);

        $order->load(['items.product', 'delivery']);

        return Inertia::render('Checkout/Success', [
            'order' => $order,
            'paymentOptions' => $this->paymentOptions(),
        ]);
    }

    public function cancel(): RedirectResponse
    {
        return redirect()
            ->route('cart')
            ->with('error', 'La commande a été annulée. Le panier est toujours disponible.');
    }

    private function authorizeOrder(Request $request, Order $order): void
    {
        $canAccess = $request->user()?->id === $order->user_id
            || (int) $request->session()->get('checkout.order_id') === $order->id;

        abort_unless($canAccess, 403, 'Cette commande ne vous appartient pas.');
    }

    private function deliveryOptions(): array
    {
        return [
            [
                'value' => 'delivery',
                'label' => 'Livraison à domicile',
                'description' => 'Disponible à Libreville et Akanda en moins de 2h.',
                'fee' => (float) config('shop.delivery_fee', 0),
                'free_from' => (float) config('shop.free_delivery_threshold', 0),
            ],
            [
                'value' => 'pickup',
                'label' => 'Retrait à l’atelier',
                'description' => 'Récupération gratuite à l’atelier Sandy.',
                'fee' => 0,
                'free_from' => 0,
            ],
        ];
    }

    private function paymentOptions(): array
    {
        return [
            Order::PAYMENT_METHOD_COD => 'Paiement à la livraison (espèces/Mobile Money)',
            Order::PAYMENT_METHOD_PICKUP => 'Paiement lors du retrait (espèces/Mobile Money)',
        ];
    }
}
