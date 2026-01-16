<?php

declare(strict_types=1);

namespace App\Domain\Susu\Models\IndividualSusu;

use App\Domain\Account\Models\Account;
use App\Domain\Account\Models\AccountAutoDebit;
use App\Domain\Account\Models\AccountCycle;
use App\Domain\Account\Models\AccountCycleDefinition;
use App\Domain\Account\Models\AccountLock;
use App\Domain\Account\Models\AccountPause;
use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Models\Wallet;
use App\Domain\Shared\Casts\MoneyCasts;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Models\Frequency;
use App\Domain\Shared\Models\HasUuid;
use Brick\Money\Money;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * Class DailySusu
 *
 * Represents a daily susu (savings) plan for an individual account.
 * Handles cycles, auto-debit events, locks, pauses, and related financial data.
 *
 * @property int $id
 * @property string $resource_id
 * @property int $individual_account_id
 * @property int|null $wallet_id
 * @property int|null $frequency_id
 *
 * @property string $currency
 * @property Money $susu_amount
 * @property Money $initial_deposit
 * @property int|null $initial_deposit_frequency
 *
 * @property Carbon|null $start_date
 * @property Carbon|null $end_date
 *
 * @property bool $rollover_enabled
 * @property bool $is_collateralized
 * @property string|null $recurring_debit_status
 * @property string|null $settlement_status
 * @property bool|null $auto_settlement
 *
 * @property string|null $action
 * @property bool|null $from_state
 * @property bool|null $to_state
 * @property Carbon|null $requested_at
 * @property Carbon|null $effective_at
 * @property string|null $initiator
 * @property int|null $initiator_id
 *
 * @property array $extra_data
 *
 * @property-read IndividualAccount $individual
 * @property-read Customer|null $customer
 * @property-read Account|HasOneThrough $account
 * @property-read Wallet|null $wallet
 * @property-read Frequency|null $frequency
 * @property-read AccountCycleDefinition|null $cycleDefinition
 * @property-read Collection|AccountCycle[] $cycles
 * @property-read Collection|AccountLock[] $accountLocks
 * @property-read AccountLock|null $activeAccountLock
 * @property-read Collection|AccountPause[] $accountPauses
 * @property-read AccountPause|null $activeAccountPause
 * @property-read Collection|AccountAutoDebit[] $autoDebits
 * @property-read AccountAutoDebit|null $lastAutoDebitEvent
 */
final class DailySusu extends Model
{
    use HasUuid;

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [
        'susu_amount' => MoneyCasts::class,
        'initial_deposit' => MoneyCasts::class,
        'extra_data' => 'array',
    ];

    protected $fillable = [
        'resource_id',
        'individual_account_id',
        'wallet_id',
        'frequency_id',

        'susu_amount',
        'initial_deposit',
        'initial_deposit_frequency',
        'currency',

        'start_date',
        'end_date',

        'rollover_enabled',
        'is_collateralized',
        'recurring_debit_status',

        'settlement_status',
        'auto_settlement',

        'extra_data',
        'action',
        'from_state',
        'to_state',
        'requested_at',
        'effective_at',
        'initiator',
        'initiator_id',
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
    public function individual(
    ): BelongsTo {
        return $this->belongsTo(
            related: IndividualAccount::class,
            foreignKey: 'individual_account_id',
        );
    }

    /**
     * @return BelongsTo
     */
    public function customer(
    ): BelongsTo {
        return $this->belongsTo(
            related: Customer::class,
            foreignKey: 'customer_id',
        );
    }

    /**
     * @return HasOneThrough
     */
    public function account(
    ): HasOneThrough {
        return $this->hasOneThrough(
            related: Account::class,
            through: IndividualAccount::class,
            firstKey: 'id',
            secondKey: 'accountable_id',
            localKey: 'individual_account_id',
            secondLocalKey: 'id'
        )->where(
            column: 'accountable_type',
            operator: IndividualAccount::class,
        );
    }

    /**
     * @return BelongsTo
     */
    public function wallet(
    ): BelongsTo {
        return $this->belongsTo(
            related: Wallet::class,
            foreignKey: 'wallet_id',
        );
    }

    /**
     * @return BelongsTo
     */
    public function frequency(
    ): BelongsTo {
        return $this->belongsTo(
            related: Frequency::class,
            foreignKey: 'frequency_id',
        );
    }

    /**
     * @return MorphOne
     */
    public function cycleDefinition(
    ): MorphOne {
        return $this->morphOne(
            related: AccountCycleDefinition::class,
            name: 'definable'
        );
    }

    /**
     * @return MorphMany
     */
    public function cycles(
    ): MorphMany {
        return $this->morphMany(
            related: AccountCycle::class,
            name: 'cycleable'
        );
    }

    /**
     * @return MorphMany
     */
    public function accountLocks(
    ): MorphMany {
        return $this->morphMany(
            related: AccountLock::class,
            name: 'lockable'
        );
    }

    /**
     * @return AccountLock|null
     */
    public function activeAccountLock(
    ): ?AccountLock {
        return $this->accountLocks()
            ->where('status', Statuses::ACTIVE->value)
            ->where(function ($query) {
                $query->whereNull('unlocked_at')
                    ->orWhere('unlocked_at', '>', Carbon::now());
            })
            ->latest('locked_at')
            ->first();
    }

    /**
     * @return bool
     */
    public function isLocked(
    ): bool {
        return $this->settlement_status === Statuses::LOCKED->value
            && $this->activeAccountLock() !== null;
    }

    /**
     * @return MorphMany
     */
    public function accountPauses(
    ): MorphMany {
        return $this->morphMany(
            related: AccountPause::class,
            name: 'pauseable'
        );
    }

    /**
     * @return AccountLock|null
     */
    public function activeAccountPause(
    ): ?AccountPause {
        return $this->accountPauses()
            ->where('status', Statuses::ACTIVE->value)
            ->where(function ($query) {
                $query->whereNull('paused_at')
                    ->orWhere('resumed_at', '>', Carbon::now());
            })
            ->latest('paused_at')
            ->first();
    }

    /**
     * @return bool
     */
    public function isPaused(
    ): bool {
        return $this->recurring_debit_status === Statuses::PAUSED->value
            && $this->activeAccountPause() !== null;
    }

    /**
     * @return string|null
     */
    public function charge(
    ): ?string {
        return $this->individual->scheme->charges->value;
    }

    /**
     * @return MorphMany
     */
    public function autoDebits(
    ): MorphMany {
        return $this->morphMany(
            related: AccountAutoDebit::class,
            name: 'debitable'
        );
    }

    /**
     * @return AccountAutoDebit|null
     */
    public function lastAutoDebitEvent(
    ): ?AccountAutoDebit {
        return $this->autoDebitEvents()
            ->latest('id')
            ->first();
    }

    /**
     * @return void
     */
    protected static function booted(
    ): void {
        DailySusu::deleting(function (DailySusu $dailySusu) {
            $dailySusu->accountLocks()->delete();
        });
        DailySusu::deleting(function (DailySusu $dailySusu) {
            $dailySusu->accountPauses()->delete();
        });
    }
}
