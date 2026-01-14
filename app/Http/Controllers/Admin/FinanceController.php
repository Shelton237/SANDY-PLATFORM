<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinanceAccount;
use App\Models\FinanceTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class FinanceController extends Controller
{
    public function index(Request $request): Response
    {
        $filters = $request->only('stage', 'account');
        $monthRange = [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()];

        $accounts = FinanceAccount::withSum(['transactions as credit_total' => function ($query) {
            $query->where('direction', 'credit');
        }], 'amount')
            ->withSum(['transactions as debit_total' => function ($query) {
                $query->where('direction', 'debit');
            }], 'amount')
            ->orderBy('name')
            ->get()
            ->map(function (FinanceAccount $account) {
                $credit = (float) ($account->credit_total ?? 0);
                $debit = (float) ($account->debit_total ?? 0);

                return [
                    'id' => $account->id,
                    'name' => $account->name,
                    'code' => $account->code,
                    'type' => $account->type,
                    'stage' => $account->stage,
                    'color' => $account->color,
                    'allocated_budget' => (float) $account->allocated_budget,
                    'balance' => round((float) $account->opening_balance + $credit - $debit, 2),
                    'credit_total' => $credit,
                    'debit_total' => $debit,
                ];
            });

        $transactionsQuery = FinanceTransaction::with('account')
            ->orderByDesc('occurred_on')
            ->orderByDesc('id');

        if ($filters['stage'] ?? false) {
            $transactionsQuery->where('stage', $filters['stage']);
        }

        if ($filters['account'] ?? false) {
            $transactionsQuery->where('finance_account_id', $filters['account']);
        }

        $transactions = $transactionsQuery->paginate(20)->withQueryString();

        $summary = [
            'month_revenue' => (float) FinanceTransaction::direction('credit')
                ->whereBetween('occurred_on', $monthRange)
                ->sum('amount'),
            'month_expenses' => (float) FinanceTransaction::direction('debit')
                ->whereBetween('occurred_on', $monthRange)
                ->sum('amount'),
            'today_spent' => (float) FinanceTransaction::direction('debit')
                ->whereDate('occurred_on', today())
                ->sum('amount'),
            'pending' => (float) FinanceTransaction::where('status', 'pending')->sum('amount'),
        ];
        $summary['net_cash'] = $summary['month_revenue'] - $summary['month_expenses'];

        $pipelines = FinanceTransaction::select([
            'stage',
            DB::raw("SUM(CASE WHEN direction = 'credit' THEN amount ELSE 0 END) AS credits"),
            DB::raw("SUM(CASE WHEN direction = 'debit' THEN amount ELSE 0 END) AS debits"),
        ])
            ->groupBy('stage')
            ->get()
            ->map(function ($row) {
                return [
                    'stage' => $row->stage,
                    'credits' => (float) $row->credits,
                    'debits' => (float) $row->debits,
                ];
            });

        $monthlyExpenseByAccount = FinanceTransaction::direction('debit')
            ->select('finance_account_id', DB::raw('SUM(amount) as total'))
            ->whereBetween('occurred_on', $monthRange)
            ->groupBy('finance_account_id')
            ->pluck('total', 'finance_account_id');

        $alerts = $accounts->filter(function ($account) use ($monthlyExpenseByAccount) {
            if ($account['type'] !== 'expense' || $account['allocated_budget'] <= 0) {
                return false;
            }

            $spend = (float) ($monthlyExpenseByAccount[$account['id']] ?? 0);

            return $spend > $account['allocated_budget'];
        })->map(function ($account) use ($monthlyExpenseByAccount) {
            $spend = (float) ($monthlyExpenseByAccount[$account['id']] ?? 0);
            $account['month_spent'] = $spend;

            return $account;
        })->values();

        return Inertia::render('Admin/Finance/Index', [
            'accounts' => $accounts,
            'transactions' => $transactions,
            'summary' => $summary,
            'pipelines' => $pipelines,
            'alerts' => $alerts,
            'filters' => $filters,
        ]);
    }

    public function storeAccount(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'code' => ['required', 'string', 'max:60', 'unique:finance_accounts,code'],
            'type' => ['required', 'string', Rule::in(['expense', 'revenue', 'asset'])],
            'stage' => ['nullable', 'string', 'max:60'],
            'color' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string'],
            'opening_balance' => ['nullable', 'numeric', 'min:0'],
            'allocated_budget' => ['nullable', 'numeric', 'min:0'],
        ]);

        FinanceAccount::create($data);

        return back()->with('success', 'Compte financier créé.');
    }

    public function storeTransaction(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'finance_account_id' => ['required', 'exists:finance_accounts,id'],
            'label' => ['required', 'string', 'max:180'],
            'direction' => ['required', 'string', Rule::in(FinanceTransaction::DIRECTIONS)],
            'amount' => ['required', 'numeric', 'min:0.1'],
            'occurred_on' => ['nullable', 'date'],
            'stage' => ['nullable', 'string', 'max:60'],
            'payment_method' => ['nullable', 'string', 'max:60'],
            'status' => ['nullable', 'string', Rule::in(FinanceTransaction::STATUSES)],
            'notes' => ['nullable', 'string'],
        ]);

        $data['status'] = $data['status'] ?? 'cleared';

        FinanceTransaction::create($data);

        return back()->with('success', 'Transaction enregistrée.');
    }

    public function updateTransaction(FinanceTransaction $transaction, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'string', Rule::in(FinanceTransaction::STATUSES)],
            'notes' => ['nullable', 'string'],
        ]);

        $transaction->update($data);

        return back()->with('success', 'Transaction mise à jour.');
    }
}
