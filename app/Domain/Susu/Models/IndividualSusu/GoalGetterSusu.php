<?php

declare(strict_types=1);

namespace App\Domain\Susu\Models\IndividualSusu;

use App\Domain\Account\Models\Account;
use App\Domain\Account\Models\AccountLock;
use App\Domain\Account\Models\AccountPause;
use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Models\Wallet;
use App\Domain\Shared\Casts\MoneyCasts;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Models\Duration;
use App\Domain\Shared\Models\Frequency;
use App\Domain\Shared\Models\HasUuid;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Class GoalGetterSusu
 *
 * @property string $id
 * @property string $resource_id
 * @property string $individual_account_id
 * @property string|null $customer_id
 * @property string $wallet_id
 * @property string $frequency_id
 * @property string $duration_id
 *
 * Monetary fields (casted via MoneyCasts):
 * @property mixed $target_amount
 * @property mixed $susu_amount
 * @property mixed $initial_deposit
 *
 * @property string $currency
 * @property string|Carbon $start_date
 * @property string|Carbon|null $end_date
 * @property bool $is_collateralized
 * @property string $withdrawal_status
 *
 * Extra data:
 * @property array|null $extra_data
 *
 * Relationships:
 * @property IndividualAccount $individual
 * @property Customer|null $customer
 * @property Account|null $account
 * @property Wallet $wallet
 * @property Frequency $frequency
 * @property Duration $duration
 *
 * @method static Builder|GoalGetterSusu whereResourceId($value)
 * @method static Builder|GoalGetterSusu whereIndividualAccountId($value)
 * @method static Builder|GoalGetterSusu whereWalletId($value)
 * @method static Builder|GoalGetterSusu whereFrequencyId($value)
 * @method static Builder|GoalGetterSusu whereDurationId($value)
 *
 * @mixin Eloquent
 */
final class GoalGetterSusu extends Model
{
    use HasUuid;

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [
        'target_amount' => MoneyCasts::class,
        'susu_amount' => MoneyCasts::class,
        'initial_deposit' => MoneyCasts::class,
        'extra_data' => 'array',
    ];

    protected $fillable = [
        'resource_id',
        'individual_account_id',
        'wallet_id',
        'frequency_id',
        'duration_id',
        'target_amount',
        'susu_amount',
        'initial_deposit',
        'currency',
        'start_date',
        'end_date',
        'is_collateralized',
        'recurring_debit_status',
        'withdrawal_status',
        'extra_data',
        'accepted_terms',
        'unlocked_at',
        'locked_at',
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
     * @return BelongsTo
     */
    public function duration(
    ): BelongsTo {
        return $this->belongsTo(
            related: Duration::class,
            foreignKey: 'duration_id'
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
        return $this->withdrawal_status === Statuses::LOCKED->value
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
     * @return void
     */
    protected static function booted(
    ): void {
        GoalGetterSusu::deleting(function (GoalGetterSusu $goalGetterSusu) {
            $goalGetterSusu->accountLocks()->delete();
        });
        GoalGetterSusu::deleting(function (GoalGetterSusu $goalGetterSusu) {
            $goalGetterSusu->accountPauses()->delete();
        });
    }
}
