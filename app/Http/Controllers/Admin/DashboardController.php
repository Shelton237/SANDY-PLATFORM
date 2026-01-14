<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\InventoryBatch;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductionBatch;
use App\Models\SupplyOrder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $metrics = [
            'orders_today' => Order::whereDate('created_at', today())->count(),
            'revenue_month' => Order::where('status', '!=', Order::STATUS_CANCELLED)
                ->whereMonth('created_at', today()->month)
                ->sum('total'),
            'products_published' => Product::where('status', Product::STATUS_PUBLISHED)->count(),
            'deliveries_today' => Delivery::whereDate('scheduled_date', today())->count(),
        ];

        $ordersTimeline = Order::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', Carbon::now()->subDays(14))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $topProducts = Product::withSum('orderItems as total_quantity', 'quantity')
            ->orderByDesc('total_quantity')
            ->take(5)
            ->get(['id', 'name', 'category', 'price']);

        $statuses = [
            'pending' => Order::where('status', Order::STATUS_PENDING)->count(),
            'production' => Order::where('status', Order::STATUS_IN_PRODUCTION)->count(),
            'ready' => Order::where('status', Order::STATUS_READY)->count(),
            'completed' => Order::where('status', Order::STATUS_COMPLETED)->count(),
        ];

        $supplyPipeline = SupplyOrder::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $inventoryHealth = [
            'available' => InventoryBatch::where('status', 'available')->sum('quantity_available'),
            'reserved' => InventoryBatch::where('status', 'reserved')->sum('quantity_available'),
            'low' => InventoryBatch::where('status', 'low')->count(),
        ];

        $productionPipeline = ProductionBatch::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $deliveryPipeline = Delivery::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return Inertia::render('Admin/Dashboard', [
            'metrics' => $metrics,
            'ordersTimeline' => $ordersTimeline,
            'topProducts' => $topProducts,
            'statuses' => $statuses,
            'supplyPipeline' => $supplyPipeline,
            'inventoryHealth' => $inventoryHealth,
            'productionPipeline' => $productionPipeline,
            'deliveryPipeline' => $deliveryPipeline,
        ]);
    }
}
