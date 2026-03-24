<?php

declare(strict_types=1);

namespace App\Domain\Susu\Models\IndividualSusu;

use App\Domain\Account\Models\Account;
use App\Domain\Account\Models\AccountCustomer;
use App\Domain\Account\Models\AccountCycleDefinition;
use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Models\Wallet;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\PaymentInstruction\Models\RecurringDeposit;
use App\Domain\Shared\Concerns\HasUuid;
use App\Domain\Transaction\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Carbon;

/**
 * Class DailySusu
 *
 * Represents a traditional individual-based Susu savings plan with a 31-day cycle,
 * where contributions are collected daily and savings become eligible for payout
 * at the end of each cycle. A new cycle starts automatically after the previous cycle ends.
 *
 * The DailySusu model manages the lifecycle of an individual’s daily savings,
 * including start and end dates, payout eligibility, automatic rollover into
 * a new cycle, optional collateralization, and additional configuration stored in metadata.
 *
 * Key Responsibilities:
 * - Associates the daily Susu plan with an Account.
 * - Tracks the 31-day savings cycle duration.
 * - Handles automatic rollover into a new cycle after payout.
 * - Indicates whether the savings plan is collateralized.
 * - Manages payout processing status and optional automatic payout.
 * - Stores flexible configuration in the metadata field.
 *
 * Routing:
 * - Uses `resource_id` as the route key for public-facing identification.
 *
 * Attributes:
 * @property int $id
 * @property string $resource_id
 * @property int $account_id
 * @property bool $is_collateralized
 * @property string|null $payout_status
 * @property bool $auto_payout
 * @property array|null $metadata
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * Relationships:
 * @property-read Account $account
 * @property-read AccountCustomer $accountCustomer
 * @property-read Wallet $linkedWallet
 * @property-read Customer $customer
 * @property-read RecurringDeposit $recurringDeposit
 * @property-read AccountCycleDefinition $accountCycleDefinition
 *
 * Domain Notes:
 * - Designed for individual daily contribution savings schemes.
 * - Each 31-day cycle accrues savings that become eligible for payout.
 * - Automatic cycle rollover ensures continuous savings without manual intervention.
 * - Collateralization and auto-payout options enable integration with credit or automated disbursement flows.
 */
final class DailySusu extends Model
{
    use HasUuid;

    protected $guarded = ['id'];

    protected $casts = [
        'is_collateralized' => 'boolean',
        'auto_payout' => 'boolean',
        'metadata' => 'array',
    ];

    protected $fillable = [
        'resource_id',
        'account_id',
        'is_collateralized',
        'payout_status',
        'auto_payout',
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
    public function account(
    ): BelongsTo {
        return $this->belongsTo(
            related: Account::class,
            foreignKey: 'account_id',
        );
    }

    /**
     * @return HasOne
     */
    public function accountCustomer(
    ): HasOne {
        return $this->hasOne(
            related: AccountCustomer::class,
            foreignKey: 'account_id',
            localKey: 'account_id'
        );
    }

    /**
     * @return Customer|null
     */
//    public function customer(
//    ): ?Customer {
//        return $this->accountCustomer?->customer;
//    }

    /**
     * @return Wallet|null
     */
//    public function linkedWallet(
//    ): ?Wallet {
//        return $this->accountCustomer?->wallet;
//    }

    /**
     * @return HasOneThrough
     */
    public function customer(
    ): HasOneThrough {
        return $this->hasOneThrough(
            related: Customer::class,
            through: AccountCustomer::class,
            firstKey: 'account_id',
            secondKey: 'id',
            localKey: 'account_id',
            secondLocalKey: 'customer_id'
        );
    }

    /**
     * @return HasOneThrough
     */
    public function linkedWallet(
    ): HasOneThrough {
        return $this->hasOneThrough(
            related: Wallet::class,
            through: AccountCustomer::class,
            firstKey: 'account_id',
            secondKey: 'id',
            localKey: 'account_id',
            secondLocalKey: 'wallet_id'
        );
    }

    /**
     * @return HasOneThrough
     */
    public function accountCycleDefinition(
    ): HasOneThrough {
        return $this->hasOneThrough(
            related: AccountCycleDefinition::class,
            through: Account::class,
            firstKey: 'id',
            secondKey: 'account_id',
            localKey: 'account_id',
            secondLocalKey: 'id'
        );
    }

    /**
     * @return HasOneThrough
     */
    public function recurringDeposit(
    ): HasOneThrough {
        return $this->hasOneThrough(
            related: RecurringDeposit::class,
            through: Account::class,
            firstKey: 'id',
            secondKey: 'account_id',
            localKey: 'account_id',
            secondLocalKey: 'id'
        );
    }

    /**
     * @return HasManyThrough
     */
    public function paymentInstructions(
    ): HasManyThrough {
        return $this->hasManyThrough(
            related: PaymentInstruction::class,
            through: Account::class,
            firstKey: 'id',
            secondKey: 'account_id',
            localKey: 'account_id',
            secondLocalKey: 'id'
        );
    }

    /**
     * @return HasManyThrough
     */
    public function transactions(
    ): HasManyThrough {
        return $this->hasManyThrough(
            related: Transaction::class,
            through: Account::class,
            firstKey: 'id',
            secondKey: 'account_id',
            localKey: 'account_id',
            secondLocalKey: 'id'
        );
    }
}
