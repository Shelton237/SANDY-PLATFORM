<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\SupplyOrder;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class SupplyController extends Controller
{
    public function index(): Response
    {
        $suppliers = Supplier::query()
            ->withCount(['supplyOrders as open_orders_count' => function ($query) {
                $query->whereIn('status', ['ordered', 'in_transit']);
            }])
            ->orderByDesc('reliability_score')
            ->get(['id', 'name', 'city', 'country', 'lead_time_days', 'reliability_score', 'status']);

        $orders = SupplyOrder::query()
            ->with('supplier:id,name')
            ->orderByDesc('ordered_at')
            ->limit(25)
            ->get();

        $stats = [
            'activeSuppliers' => $suppliers->where('status', '!=', 'on_hold')->count(),
            'inTransit' => $orders->where('status', 'in_transit')->count(),
            'qualityAlerts' => $orders->where('quality_status', 'rework')->count(),
            'arrivalsToday' => SupplyOrder::whereDate('expected_at', Carbon::today())->count(),
        ];

        $pipeline = SupplyOrder::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return Inertia::render('Admin/Supply/Index', [
            'suppliers' => $suppliers,
            'orders' => $orders,
            'stats' => $stats,
            'pipeline' => $pipeline,
        ]);
    }
}
