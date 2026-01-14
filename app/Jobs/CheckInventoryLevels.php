<?php

namespace App\Jobs;

use App\Models\InventoryBatch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckInventoryLevels implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $threshold = 20)
    {
    }

    public function handle(): void
    {
        $lowBatches = InventoryBatch::query()
            ->where('quantity_available', '<', $this->threshold)
            ->orWhere('status', 'low')
            ->get();

        foreach ($lowBatches as $batch) {
            if ($batch->status !== 'low') {
                $batch->update(['status' => 'low']);
            }
        }

        if ($lowBatches->isNotEmpty()) {
            Log::warning('Stock bas détecté', [
                'threshold' => $this->threshold,
                'batches' => $lowBatches->pluck('batch_code'),
            ]);
        }
    }
}
