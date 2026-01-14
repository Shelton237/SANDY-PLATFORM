<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductionBatch;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ProductionController extends Controller
{
    public function index(): Response
    {
        $batches = ProductionBatch::with('product:id,name,category')
            ->orderByDesc('starts_at')
            ->limit(20)
            ->get();

        $statusCounts = ProductionBatch::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $focusProducts = $batches
            ->where('status', 'in_progress')
            ->map(fn ($batch) => $batch->product?->name)
            ->filter()
            ->values();

        $stats = [
            'planned' => $statusCounts['planned'] ?? 0,
            'inProgress' => $statusCounts['in_progress'] ?? 0,
            'quality' => $statusCounts['quality_check'] ?? 0,
            'packaged' => $statusCounts['packaged'] ?? 0,
            'currentFocus' => $focusProducts,
        ];

        $products = Product::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        return Inertia::render('Admin/Production/Index', [
            'batches' => $batches,
            'stats' => $stats,
            'products' => $products,
        ]);
    }
}
