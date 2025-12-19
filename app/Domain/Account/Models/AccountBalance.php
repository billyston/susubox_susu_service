<?php

declare(strict_types=1);

namespace App\Domain\Account\Models;

use App\Domain\Shared\Casts\MoneyCasts;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class AccountBalance
 *
 * @property string $id
 * @property string $resource_id
 * @property string $account_id
 *
 * Monetary fields (casted via MoneyCasts):
 * @property mixed $ledger_balance
 * @property mixed $available_balance
 * @property mixed $pending_debit_balance
 * @property mixed $pending_credit_balance
 *
 * @property string|null $last_transaction_id
 * @property Carbon|null $last_reconciled_at
 *
 * Relationships:
 * @property Account $account
 *
 * @method static Builder|AccountBalance whereAccountId($value)
 * @method static Builder|AccountBalance whereLastTransactionId($value)
 * @method static Builder|AccountBalance whereLastReconciledAt($value)
 *
 * @mixin \Eloquent
 */
final class AccountBalance extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'ledger_balance' => MoneyCasts::class,
        'available_balance' => MoneyCasts::class,
    ];

    protected $fillable = [
        'account_id',
        'ledger_balance',
        'available_balance',
        'currency',
        'last_transaction_id',
        'last_reconciled_at',
    ];

    /**
     * @return string
     */
    public function getRouteKeyName(
    ): string {
        return 'resource_id';
    }

    /**
     * @return BelongsTo
     */
    public function account(
    ): BelongsTo {
        return $this->belongsTo(
            related: Account::class,
            foreignKey: 'account_id'
        );
    }
}
