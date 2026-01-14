<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

class FinanceTransaction extends Model
{
    use HasFactory;

    public const DIRECTIONS = ['debit', 'credit'];
    public const STATUSES = ['pending', 'cleared', 'flagged'];

    protected $fillable = [
        'finance_account_id',
        'reference',
        'label',
        'direction',
        'amount',
        'currency',
        'occurred_on',
        'stage',
        'status',
        'payment_method',
        'transactionable_id',
        'transactionable_type',
        'metadata',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'occurred_on' => 'date',
        'metadata' => 'array',
    ];

    protected $appends = [
        'signed_amount',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $transaction) {
            if (empty($transaction->reference)) {
                $transaction->reference = self::generateReference();
            }

            if (empty($transaction->currency)) {
                $transaction->currency = 'FCFA';
            }

            if (empty($transaction->occurred_on)) {
                $transaction->occurred_on = now();
            }
        });
    }

    public static function generateReference(): string
    {
        return 'FIN-' . Str::upper(Str::random(6));
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(FinanceAccount::class, 'finance_account_id');
    }

    public function transactionable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeDirection($query, string $direction)
    {
        return $query->where('direction', $direction);
    }

    public function getSignedAmountAttribute(): float
    {
        $amount = (float) $this->amount;

        return $this->direction === 'debit' ? -1 * $amount : $amount;
    }
}
