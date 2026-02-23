<?php

declare(strict_types=1);

namespace App\Domain\PaymentInstruction\Models;

use App\Domain\Account\Models\Account;
use App\Domain\Account\Models\AccountCustomer;
use App\Domain\Shared\Casts\MoneyCasts;
use App\Domain\Shared\Models\Frequency;
use App\Domain\Shared\Models\HasUuid;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * Class RecurringDeposit
 *
 * Represents a customer-initiated recurring deposit within an Account.
 *
 * The RecurringDeposit model tracks deposits scheduled at defined
 * frequencies for an AccountCustomer. It links the recurring deposit
 * to a PaymentInstruction, the owning account, and the customer.
 * This model supports structured savings plans where contributions
 * recur automatically over time, optionally with rollovers.
 *
 * Key Responsibilities:
 * - Stores recurring deposit amounts, initial amounts, and currency.
 * - Tracks the frequency and initial frequency of contributions.
 * - Supports rollover of deposits with tracking of rollover count.
 * - Maintains the lifecycle status of the recurring deposit.
 * - Connects to pauses, enabling temporary suspension of recurring deposits.
 * - Determines whether the recurring deposit is currently paused.
 *
 * Behavioral Notes:
 * - `rollover_enabled` indicates if failed deposits amounts are automatically carried forward.
 * - Pauses are tracked via RecurringDepositPause relationships, with `recurringDepositActivePause` identifying currently active pauses.
 *
 * Routing:
 * - Uses `resource_id` as the route key for public-facing identification.
 *
 * Attributes:
 * @property int $id
 * @property string $resource_id
 * @property int $account_id
 * @property int|null $account_customer_id
 * @property int $payment_instruction_id
 * @property int $frequency_id
 * @property Money $recurring_amount
 * @property Money|null $initial_amount
 * @property int|null $initial_frequency
 * @property string $currency
 * @property bool $rollover_enabled
 * @property int|null $rollover_count
 * @property string $status
 * @property array|null $metadata
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * Relationships:
 * @property-read PaymentInstruction $paymentInstruction
 * @property-read Frequency $frequency
 * @property-read Account $account
 * @property-read AccountCustomer|null $accountCustomer
 * @property-read Collection|RecurringDepositPause[] $recurringDepositPauses
 * @property-read RecurringDepositPause|null $recurringDepositActivePause
 *
 * Helper Methods:
 * - isRecurringDepositPaused(): Returns true if there is an active pause in effect.
 *
 * Domain Notes:
 * - This model empowers customers to automate disciplined savings.
 * - Pauses allow temporary suspension without cancelling the recurring deposit.
 */
final class RecurringDeposit extends Model
{
    use HasUuid;

    protected $guarded = ['id'];

    protected $casts = [
        'recurring_amount' => MoneyCasts::class,
        'initial_amount' => MoneyCasts::class,
        'rollover_enabled' => 'boolean',
        'metadata' => 'array',
    ];

    protected $fillable = [
        'resource_id',
        'account_id',
        'account_customer_id',
        'payment_instruction_id',
        'frequency_id',
        'recurring_amount',
        'initial_amount',
        'initial_frequency',
        'currency',
        'rollover_enabled',
        'rollover_count',
        'status',
        'metadata',
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
    public function frequency(
    ): BelongsTo {
        return $this->belongsTo(
            related: Frequency::class,
            foreignKey: 'frequency_id'
        );
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
     * @return HasMany
     */
    public function recurringDepositPauses(
    ): HasMany {
        return $this->hasMany(
            related: RecurringDepositPause::class,
            foreignKey: 'recurring_deposit_pause_id'
        );
    }

    /**
     * @return HasOne
     */
    public function recurringDepositActivePause(
    ): HasOne {
        return $this->hasOne(
            related: RecurringDepositPause::class,
            foreignKey: 'recurring_deposit_pause_id'
        )
            ->where('status', 'active')
            ->where('expires_at', '>', now());
    }

    /**
     * @return bool
     */
    public function isRecurringDepositPaused(
    ): bool {
        return $this->recurringDepositActivePause()->exists();
    }
}
