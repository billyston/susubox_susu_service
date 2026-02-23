<?php

declare(strict_types=1);

namespace App\Domain\Account\Models;

use App\Domain\Shared\Casts\MoneyCasts;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class AccountBalance
 *
 * Represents the authoritative financial balance record for an Account.
 *
 * The AccountBalance model maintains both the ledger balance and the
 * available balance of an account in a specific currency. It acts as
 * the single source of truth for account funds and is typically updated
 * atomically alongside transactions to ensure financial consistency.
 *
 * Key Responsibilities:
 * - Stores the total ledger balance (all posted transactions).
 * - Stores the available balance (spendable funds after holds/locks).
 * - Tracks the last processed transaction for reconciliation integrity.
 * - Records the timestamp of the last reconciliation.
 * - Enforces strong money typing via custom money casting.
 *
 * Financial Integrity Notes:
 * - `ledger_balance` reflects the sum of all committed debits and credits.
 * - `available_balance` reflects funds available for withdrawal or settlement.
 * - Updates to balances should occur within database transactions to prevent race
 * conditions and negative balance inconsistencies.
 *
 * Routing:
 * - Uses `resource_id` as the route key for public-facing identification.
 *
 * Attributes:
 * @property int $id
 * @property int $account_id
 * @property Money $ledger_balance
 * @property Money $available_balance
 * @property string $currency
 * @property int|null $last_transaction_id
 * @property Carbon|null $last_reconciled_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * Relationships:
 * @property-read Account $account
 */
final class AccountBalance extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'available_balance' => MoneyCasts::class,
        'ledger_balance' => MoneyCasts::class,
        'last_reconciled_at' => 'timestamp',
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
