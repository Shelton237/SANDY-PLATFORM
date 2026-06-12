<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $metrics = [
            'orders_today'       => Order::whereDate('created_at', today())->count(),
            'orders_pending'     => Order::where('status', Order::STATUS_PENDING)->count(),
            'revenue_month'      => Order::where('status', '!=', Order::STATUS_CANCELLED)
                ->whereMonth('created_at', today()->month)
                ->sum('total'),
            'products_published' => Product::where('status', Product::STATUS_PUBLISHED)->count(),
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
            'pending'     => Order::where('status', Order::STATUS_PENDING)->count(),
            'production'  => Order::where('status', Order::STATUS_IN_PRODUCTION)->count(),
            'ready'       => Order::where('status', Order::STATUS_READY)->count(),
            'completed'   => Order::where('status', Order::STATUS_COMPLETED)->count(),
            'cancelled'   => Order::where('status', Order::STATUS_CANCELLED)->count(),
        ];

        $recentOrders = Order::with('delivery')
            ->orderByDesc('placed_at')
            ->take(8)
            ->get(['id', 'number', 'customer_name', 'status', 'total', 'placed_at']);

        $deliveryPipeline = Delivery::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return Inertia::render('Admin/Dashboard', [
            'metrics'          => $metrics,
            'ordersTimeline'   => $ordersTimeline,
            'topProducts'      => $topProducts,
            'statuses'         => $statuses,
            'recentOrders'     => $recentOrders,
            'deliveryPipeline' => $deliveryPipeline,
        ]);
    }
}
