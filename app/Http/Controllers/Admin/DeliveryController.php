<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class DeliveryController extends Controller
{
    public function index(Request $request): Response
    {
        $filters = $request->only('status');

        $deliveries = Delivery::query()
            ->with(['order'])
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->orderByRaw('scheduled_date IS NULL')
            ->orderBy('scheduled_date')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'pending' => Delivery::where('status', Delivery::STATUS_PENDING)->count(),
            'scheduled' => Delivery::where('status', Delivery::STATUS_SCHEDULED)->count(),
            'dispatched' => Delivery::where('status', Delivery::STATUS_DISPATCHED)->count(),
            'delivered_today' => Delivery::where('status', Delivery::STATUS_DELIVERED)
                ->whereDate('delivered_at', today())
                ->count(),
        ];

        return Inertia::render('Admin/Deliveries/Index', [
            'deliveries' => $deliveries,
            'filters' => $filters,
            'statusOptions' => Delivery::statuses(),
            'stats' => $stats,
        ]);
    }

    public function show(Delivery $delivery): Response
    {
        $delivery->load('order.items');

        return Inertia::render('Admin/Deliveries/Show', [
            'delivery' => $delivery,
            'statusOptions' => Delivery::statuses(),
        ]);
    }

    public function update(Request $request, Delivery $delivery): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'in:' . implode(',', array_keys(Delivery::statuses()))],
            'scheduled_date' => ['nullable', 'date'],
            'time_window' => ['nullable', 'string', 'max:120'],
            'courier_name' => ['nullable', 'string', 'max:120'],
            'courier_phone' => ['nullable', 'string', 'max:50'],
            'vehicle_type' => ['nullable', 'string', 'max:120'],
            'tracking_link' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $delivery->fill($data);

        if ($delivery->status === Delivery::STATUS_DISPATCHED && empty($delivery->dispatched_at)) {
            $delivery->dispatched_at = Carbon::now();
        }

        if ($delivery->status === Delivery::STATUS_DELIVERED) {
            $delivery->delivered_at = Carbon::now();
        }

        $delivery->save();

        return redirect()
            ->route('admin.deliveries.index')
            ->with('success', 'Livraison mise à jour.');
    }
}
