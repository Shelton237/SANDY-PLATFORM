<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\CheckInventoryLevels;
use App\Models\InventoryBatch;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InventoryBatchController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'ingredient' => ['required', 'string', 'max:120'],
            'quantity_available' => ['required', 'numeric', 'min:0.1'],
            'unit' => ['required', 'string', 'max:25'],
            'location' => ['nullable', 'string', 'max:120'],
            'status' => ['nullable', 'string', 'max:60'],
            'expires_at' => ['nullable', 'date'],
        ]);

        $data['batch_code'] = 'LOT-' . Str::upper(Str::random(5));
        $data['status'] = $data['status'] ?? 'available';
        $data['received_at'] = now();

        InventoryBatch::create($data);

        CheckInventoryLevels::dispatchSync(20);

        return back()->with('success', 'Lot ajouté en stock.');
    }

    public function update(Request $request, InventoryBatch $inventoryBatch): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['nullable', 'string', 'max:60'],
            'location' => ['nullable', 'string', 'max:120'],
            'quantity_available' => ['nullable', 'numeric', 'min:0'],
        ]);

        $inventoryBatch->update($data);

        CheckInventoryLevels::dispatchSync(20);

        return back()->with('success', 'Lot mis à jour.');
    }

    public function reserve(Request $request, InventoryBatch $inventoryBatch): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'planned_liters' => ['required', 'numeric', 'min:1'],
        ]);

        $product = Product::findOrFail($data['product_id']);

        $inventoryBatch->update([
            'status' => 'reserved',
            'reserved_for' => $product->name,
        ]);

        \App\Models\ProductionBatch::create([
            'product_id' => $product->id,
            'inventory_batch_id' => $inventoryBatch->id,
            'code' => 'PB-' . Str::upper(Str::random(6)),
            'status' => 'planned',
            'planned_liters' => $data['planned_liters'],
            'team_lead' => $request->input('team_lead'),
            'shift' => $request->input('shift'),
            'notes' => 'Automatisation depuis lot ' . $inventoryBatch->batch_code,
        ]);

        CheckInventoryLevels::dispatchSync(20);

        return back()->with('success', 'Lot réservé et batch production planifié.');
    }

    public function destroy(InventoryBatch $inventoryBatch): RedirectResponse
    {
        $inventoryBatch->delete();

        CheckInventoryLevels::dispatchSync(20);

        return back()->with('success', 'Lot supprimé.');
    }
}
