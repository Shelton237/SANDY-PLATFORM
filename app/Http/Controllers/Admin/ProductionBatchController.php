<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductionBatch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductionBatchController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'planned_liters' => ['required', 'numeric', 'min:1'],
            'team_lead' => ['nullable', 'string', 'max:255'],
            'shift' => ['nullable', 'string', 'max:120'],
            'notes' => ['nullable', 'string'],
        ]);

        $data['code'] = 'PB-' . Str::upper(Str::random(6));
        $data['status'] = 'planned';
        $data['starts_at'] = now();

        ProductionBatch::create($data);

        return back()->with('success', 'Batch de production planifié.');
    }

    public function update(Request $request, ProductionBatch $productionBatch): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['nullable', 'string'],
            'actual_liters' => ['nullable', 'numeric', 'min:0'],
            'yield_percent' => ['nullable', 'integer', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        if (($data['status'] ?? null) === 'packaged' && empty($productionBatch->ends_at)) {
            $data['ends_at'] = now();
        }

        $productionBatch->update($data);

        return back()->with('success', 'Batch mis à jour.');
    }

    public function destroy(ProductionBatch $productionBatch): RedirectResponse
    {
        $productionBatch->delete();

        return back()->with('success', 'Batch supprimé.');
    }
}
