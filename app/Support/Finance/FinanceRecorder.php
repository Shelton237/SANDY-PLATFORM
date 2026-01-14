<?php

namespace App\Support\Finance;

use App\Models\FinanceAccount;
use App\Models\FinanceTransaction;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class FinanceRecorder
{
    public static function record(string $accountCode, array $attributes): ?FinanceTransaction
    {
        $account = FinanceAccount::findByCode($accountCode);

        if (!$account) {
            return null;
        }

        $payload = array_merge([
            'finance_account_id' => $account->id,
            'reference' => FinanceTransaction::generateReference(),
            'currency' => 'FCFA',
            'status' => 'cleared',
            'occurred_on' => now()->toDateString(),
        ], $attributes);

        if (empty($payload['label']) && !empty($payload['stage'])) {
            $payload['label'] = Str::studly($payload['stage']) . ' transaction';
        }

        return FinanceTransaction::create($payload);
    }

    public static function recordSupplyExpense(array $data): ?FinanceTransaction
    {
        return self::record('supply_costs', array_merge([
            'direction' => 'debit',
            'stage' => 'supply',
        ], $data));
    }

    public static function recordSalesRevenue(array $data): ?FinanceTransaction
    {
        return self::record('sales_revenue', array_merge([
            'direction' => 'credit',
            'stage' => 'sales',
        ], $data));
    }

    public static function recordCashMovement(array $data): ?FinanceTransaction
    {
        return self::record('cash', $data);
    }
}
