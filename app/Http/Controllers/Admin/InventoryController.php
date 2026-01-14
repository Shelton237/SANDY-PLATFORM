<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryBatch;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class InventoryController extends Controller
{
    public function index(): Response
    {
        $batches = InventoryBatch::with('supplier:id,name')
            ->orderByDesc('received_at')
            ->get();

        $stats = [
            'totalKg' => $batches->sum('quantity_available'),
            'lowStock' => $batches->where('status', 'low')->count(),
            'reserved' => $batches->where('status', 'reserved')->count(),
            'expiringSoon' => $batches->whereBetween('expires_at', [now(), now()->addDays(5)])->count(),
        ];

        $statusBreakdown = InventoryBatch::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $locations = InventoryBatch::select('location', DB::raw('sum(quantity_available) as total'))
            ->groupBy('location')
            ->orderByDesc('total')
            ->get()
            ->toArray();

        $products = Product::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        return Inertia::render('Admin/Inventory/Index', [
            'batches' => $batches,
            'stats' => $stats,
            'statusBreakdown' => $statusBreakdown,
            'locations' => $locations,
            'products' => $products,
        ]);
    }
}
