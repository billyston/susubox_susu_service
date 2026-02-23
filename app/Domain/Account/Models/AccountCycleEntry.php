<?php

declare(strict_types=1);

namespace App\Domain\Account\Models;

use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Shared\Casts\MoneyCasts;
use App\Domain\Shared\Models\HasUuid;
use App\Domain\Transaction\Models\Transaction;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class AccountCycleEntry
 *
 * Represents a single contribution entry within an AccountCycle.
 *
 * The AccountCycleEntry model records an individual contribution made
 * by an AccountCustomer toward a specific AccountCycle. It acts as the
 * atomic financial unit of a cycle, linking together the customer,
 * cycle, payment instruction, and underlying transaction.
 *
 * Each entry contributes to:
 * - The cumulative `contributed_amount` of the AccountCycle.
 * - The `completed_frequencies` count of the cycle.
 *
 * This model ensures traceability between contribution intent
 * (PaymentInstruction), execution (Transaction), and cycle aggregation.
 *
 * Key Responsibilities:
 * - Records the monetary contribution amount for a cycle.
 * - Tracks how many frequencies the entry satisfies.
 * - Links to the originating payment instruction.
 * - Links to the finalized transaction record.
 * - Maintains lifecycle status and posting timestamp.
 *
 * Financial Integrity Notes:
 * - `amount` must align with the cycle's currency.
 * - `frequencies` determines how much of the cycle obligation this entry fulfills.
 * - `posted_at` represents when the entry was confirmed and applied.
 * - Entries should only be marked successful after the linked transaction is finalized.
 *
 * Routing:
 * - Uses `resource_id` as the route key for public-facing identification.
 *
 * Attributes:
 * @property int $id
 * @property string $resource_id
 * @property int $account_customer_id
 * @property int $account_cycle_id
 * @property int|null $payment_instruction_id
 * @property int|null $transaction_id
 * @property Money $amount
 * @property string $currency
 * @property int $frequencies
 * @property string $entry_type
 * @property Carbon|null $posted_at
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * Relationships:
 * @property-read AccountCycle $accountCycle
 * @property-read AccountCustomer $accountCustomer
 * @property-read PaymentInstruction|null $paymentInstruction
 * @property-read Transaction|null $transaction
 *
 * Domain Notes:
 * - This model provides granular auditability for cycle-based savings.
 * - Multiple entries may exist per customer per cycle depending on contribution frequency and structure.
 */
final class AccountCycleEntry extends Model
{
    use HasUuid;

    protected $guarded = ['id'];

    protected $casts = [
        'amount' => MoneyCasts::class,
        'posted_at' => 'datetime',
    ];

    protected $fillable = [
        'resource_id',
        'account_customer_id',
        'account_cycle_id',
        'payment_instruction_id',
        'transaction_id',
        'amount',
        'currency',
        'frequencies',
        'entry_type',
        'posted_at',
        'status',
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
    public function accountCycle(
    ): BelongsTo {
        return $this->belongsTo(
            related: AccountCycle::class,
            foreignKey: 'account_cycle_id'
        );
    }

    /**
     * @return BelongsTo
     */
    public function accountCustomer(
    ): BelongsTo {
        return $this->belongsTo(
            related: AccountCustomer::class,
            foreignKey: 'account_customer_id'
        );
    }

    /**
     * @return BelongsTo
     */
    public function paymentInstruction(
    ): BelongsTo {
        return $this->belongsTo(
            related: PaymentInstruction::class,
            foreignKey: 'payment_instruction_id'
        );
    }

    /**
     * @return BelongsTo
     */
    public function transaction(
    ): BelongsTo {
        return $this->belongsTo(
            related: Transaction::class,
            foreignKey: 'transaction_id'
        );
    }
}
