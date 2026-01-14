<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\FinanceTransaction;
use App\Support\Finance\FinanceRecorder;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function index(Request $request): Response
    {
        $filters = $request->only('search', 'status', 'payment_status');

        $orders = Order::query()
            ->with(['delivery'])
            ->withCount('items')
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('number', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_email', 'like', "%{$search}%");
                });
            })
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->when($filters['payment_status'] ?? null, fn ($query, $payment) => $query->where('payment_status', $payment))
            ->orderByDesc('placed_at')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'pending' => Order::where('status', Order::STATUS_PENDING)->count(),
            'production' => Order::where('status', Order::STATUS_IN_PRODUCTION)->count(),
            'completed' => Order::whereDate('completed_at', today())->count(),
            'revenue' => Order::where('status', '!=', Order::STATUS_CANCELLED)->sum('total'),
        ];

        return Inertia::render('Admin/Orders/Index', [
            'orders' => $orders,
            'filters' => $filters,
            'stats' => $stats,
            'statusOptions' => Order::statuses(),
            'paymentOptions' => Order::paymentStatuses(),
            'deliveryStatuses' => Delivery::statuses(),
            'products' => Product::select('id', 'name', 'price')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function show(Order $order): Response
    {
        $order->load([
            'items.product',
            'delivery',
        ]);

        return Inertia::render('Admin/Orders/Show', [
            'order' => $order,
            'statusOptions' => Order::statuses(),
            'paymentOptions' => Order::paymentStatuses(),
            'deliveryStatuses' => Delivery::statuses(),
            'paymentMethods' => Order::paymentMethods(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $statusKeys = array_keys(Order::statuses());
        $paymentKeys = array_keys(Order::paymentStatuses());
        $paymentMethodKeys = array_keys(Order::paymentMethods());

        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:255'],
            'address_line1' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'delivery_fee' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', Rule::in($statusKeys)],
            'payment_status' => ['required', Rule::in($paymentKeys)],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'numeric', 'min:1'],
            'delivery' => ['nullable', 'array'],
            'delivery.scheduled_date' => ['nullable', 'date'],
            'delivery.time_window' => ['nullable', 'string', 'max:255'],
            'delivery.notes' => ['nullable', 'string'],
            'payment_method' => ['nullable', Rule::in($paymentMethodKeys)],
        ]);

        $order = DB::transaction(function () use ($data) {
            $productMap = Product::whereIn('id', collect($data['items'])->pluck('product_id'))
                ->get()
                ->keyBy('id');

            $orderItems = [];
            $subtotal = 0;

            foreach ($data['items'] as $item) {
                $product = $productMap->get($item['product_id']);

                if (!$product) {
                    continue;
                }

                $quantity = (int) $item['quantity'];
                $unitPrice = (float) $product->price;
                $lineTotal = $unitPrice * $quantity;
                $subtotal += $lineTotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $lineTotal,
                ];
            }

            if (empty($orderItems)) {
                throw ValidationException::withMessages([
                    'items' => 'Veuillez choisir au moins un produit valide.',
                ]);
            }

            $deliveryFee = (float) ($data['delivery_fee'] ?? 0);
            $confirmedAt = in_array($data['status'], [
                Order::STATUS_CONFIRMED,
                Order::STATUS_IN_PRODUCTION,
                Order::STATUS_READY,
                Order::STATUS_COMPLETED,
            ]) ? now() : null;

            $completedAt = $data['status'] === Order::STATUS_COMPLETED ? now() : null;

            $order = Order::create([
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'] ?? null,
                'address_line1' => $data['address_line1'] ?? null,
                'city' => $data['city'] ?? null,
                'notes' => $data['notes'] ?? null,
                'status' => $data['status'],
                'payment_status' => $data['payment_status'],
                'payment_method' => $data['payment_method'] ?? Order::PAYMENT_METHOD_COD,
                'delivery_status' => Delivery::STATUS_PENDING,
                'delivery_type' => 'delivery',
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'total' => $subtotal + $deliveryFee,
                'confirmed_at' => $confirmedAt,
                'completed_at' => $completedAt,
            ]);

            $order->items()->createMany($orderItems);

            if (!empty($data['delivery'])) {
                $order->delivery()->create([
                    'status' => Delivery::STATUS_PENDING,
                    'scheduled_date' => $data['delivery']['scheduled_date'] ?? null,
                    'time_window' => $data['delivery']['time_window'] ?? null,
                    'notes' => $data['delivery']['notes'] ?? null,
                    'address_line1' => $order->address_line1,
                    'city' => $order->city,
                ]);
            }

            return $order;
        });

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Commande créée avec succès.');
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['nullable', 'string', 'in:' . implode(',', array_keys(Order::statuses()))],
            'payment_status' => ['nullable', 'string', 'in:' . implode(',', array_keys(Order::paymentStatuses()))],
            'delivery_status' => ['nullable', 'string', 'in:' . implode(',', array_keys(Delivery::statuses()))],
            'notes' => ['nullable', 'string'],
            'payment_method' => ['nullable', 'string', 'in:' . implode(',', array_keys(Order::paymentMethods()))],
        ]);

        if (!empty($data['status']) && $data['status'] !== $order->status) {
            $order->status = $data['status'];

            if ($data['status'] === Order::STATUS_CONFIRMED && empty($order->confirmed_at)) {
                $order->confirmed_at = Carbon::now();
            }

            if ($data['status'] === Order::STATUS_COMPLETED) {
                $order->completed_at = Carbon::now();
            }
        }

        if (!empty($data['payment_status'])) {
            $order->payment_status = $data['payment_status'];
        }

        if (!empty($data['notes'])) {
            $order->notes = $data['notes'];
        }

        if (!empty($data['payment_method'])) {
            $order->payment_method = $data['payment_method'];
        }

        $order->save();

        if (!empty($data['delivery_status']) && $order->delivery) {
            $order->delivery->update([
                'status' => $data['delivery_status'],
                'dispatched_at' => $data['delivery_status'] === Delivery::STATUS_DISPATCHED
                    ? Carbon::now()
                    : $order->delivery->dispatched_at,
                'delivered_at' => $data['delivery_status'] === Delivery::STATUS_DELIVERED
                    ? Carbon::now()
                    : $order->delivery->delivered_at,
            ]);
        }

        if ($this->shouldRecordRevenue($order)) {
            $exists = FinanceTransaction::where('transactionable_type', Order::class)
                ->where('transactionable_id', $order->id)
                ->exists();

            if (!$exists) {
                FinanceRecorder::recordSalesRevenue([
                    'label' => "Commande {$order->number}",
                    'amount' => $order->total,
                    'occurred_on' => $order->completed_at ?? now(),
                    'transactionable_id' => $order->id,
                    'transactionable_type' => Order::class,
                    'notes' => 'Flux généré après validation backoffice.',
                ]);
            }
        }

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Commande mise à jour.');
    }

    private function shouldRecordRevenue(Order $order): bool
    {
        return $order->status === Order::STATUS_COMPLETED && $order->payment_status === Order::PAYMENT_PAID;
    }
}
