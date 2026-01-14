<?php

namespace Database\Seeders;

use App\Models\FinanceAccount;
use App\Models\FinanceTransaction;
use App\Models\Order;
use App\Models\SupplyOrder;
use Illuminate\Database\Seeder;

class FinanceSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            [
                'name' => 'Trésorerie',
                'code' => 'cash',
                'type' => 'asset',
                'stage' => 'admin',
                'color' => '#0f766e',
                'opening_balance' => 500000,
            ],
            [
                'name' => 'Achats ingrédients',
                'code' => 'supply_costs',
                'type' => 'expense',
                'stage' => 'supply',
                'allocated_budget' => 300000,
            ],
            [
                'name' => 'Production',
                'code' => 'production_costs',
                'type' => 'expense',
                'stage' => 'production',
                'allocated_budget' => 200000,
            ],
            [
                'name' => 'Distribution & Livraison',
                'code' => 'delivery_costs',
                'type' => 'expense',
                'stage' => 'delivery',
                'allocated_budget' => 150000,
            ],
            [
                'name' => 'Revenus ventes',
                'code' => 'sales_revenue',
                'type' => 'revenue',
                'stage' => 'sales',
            ],
        ];

        foreach ($accounts as $account) {
            FinanceAccount::updateOrCreate(['code' => $account['code']], $account);
        }

        $supplyAccount = FinanceAccount::findByCode('supply_costs');
        if ($supplyAccount) {
            SupplyOrder::query()
                ->whereNotNull('total_cost')
                ->take(6)
                ->each(function (SupplyOrder $order) use ($supplyAccount) {
                    FinanceTransaction::firstOrCreate(
                        [
                            'finance_account_id' => $supplyAccount->id,
                            'transactionable_id' => $order->id,
                            'transactionable_type' => SupplyOrder::class,
                        ],
                        [
                            'label' => "Commande {$order->reference}",
                            'direction' => 'debit',
                            'amount' => $order->total_cost,
                            'occurred_on' => $order->ordered_at ?? now(),
                            'stage' => 'supply',
                            'status' => 'cleared',
                        ],
                    );
                });
        }

        $salesAccount = FinanceAccount::findByCode('sales_revenue');
        if ($salesAccount) {
            Order::query()
                ->where('status', Order::STATUS_COMPLETED)
                ->take(8)
                ->each(function (Order $order) use ($salesAccount) {
                    FinanceTransaction::firstOrCreate(
                        [
                            'finance_account_id' => $salesAccount->id,
                            'transactionable_id' => $order->id,
                            'transactionable_type' => Order::class,
                        ],
                        [
                            'label' => "Vente {$order->number}",
                            'direction' => 'credit',
                            'amount' => $order->total,
                            'occurred_on' => $order->completed_at ?? now(),
                            'stage' => 'sales',
                            'status' => 'cleared',
                        ],
                    );
                });
        }
    }
}
