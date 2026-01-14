<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\CheckInventoryLevels;
use App\Models\InventoryBatch;
use App\Models\Supplier;
use App\Models\SupplyOrder;
use App\Support\Finance\FinanceRecorder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SupplyOrderController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'ingredient' => ['required', 'string', 'max:120'],
            'quantity' => ['required', 'numeric', 'min:0.1'],
            'unit' => ['required', 'string', 'max:25'],
            'transport_mode' => ['nullable', 'string', 'max:120'],
            'storage_location' => ['nullable', 'string', 'max:120'],
            'expected_at' => ['nullable', 'date'],
            'total_cost' => ['nullable', 'numeric', 'min:0'],
        ]);

        $data['reference'] = 'PO-' . Str::upper(Str::random(6));
        $data['status'] = 'ordered';
        $data['ordered_at'] = now();

        $supplyOrder = SupplyOrder::create($data);

        if (!empty($data['total_cost'])) {
            FinanceRecorder::recordSupplyExpense([
                'label' => "Commande {$supplyOrder->reference}",
                'amount' => $data['total_cost'],
                'occurred_on' => $data['ordered_at'] ?? now()->toDateString(),
                'transactionable_id' => $supplyOrder->id,
                'transactionable_type' => SupplyOrder::class,
                'notes' => 'Saisie depuis la fiche appro.',
            ]);
        }

        return back()->with('success', 'Commande d’ingrédients créée.');
    }

    public function update(Request $request, SupplyOrder $supplyOrder): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['nullable', 'string'],
            'quality_status' => ['nullable', 'string'],
            'expected_at' => ['nullable', 'date'],
        ]);

        $supplyOrder->update($data);

        return back()->with('success', 'Commande mise à jour.');
    }

    public function receive(Request $request, SupplyOrder $supplyOrder): RedirectResponse
    {
        $request->validate([
            'location' => ['nullable', 'string', 'max:120'],
        ]);

        $supplyOrder->update([
            'status' => 'received',
            'quality_status' => 'valid',
            'received_at' => now(),
        ]);

        $batch = InventoryBatch::create([
            'supplier_id' => $supplyOrder->supplier_id,
            'batch_code' => 'LOT-' . Str::upper(Str::random(5)),
            'ingredient' => $supplyOrder->ingredient,
            'quantity_available' => $supplyOrder->quantity,
            'unit' => $supplyOrder->unit,
            'status' => 'available',
            'location' => $request->input('location', $supplyOrder->storage_location),
            'received_at' => now(),
            'expires_at' => now()->addDays(7),
            'metadata' => [
                'source_order' => $supplyOrder->reference,
            ],
        ]);

        CheckInventoryLevels::dispatchSync(20);

        return back()->with('success', "Commande réceptionnée et lot {$batch->batch_code} créé.");
    }

    public function destroy(SupplyOrder $supplyOrder): RedirectResponse
    {
        $supplyOrder->delete();

        return back()->with('success', 'Commande supprimée.');
    }
}
