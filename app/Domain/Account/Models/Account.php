<?php

declare(strict_types=1);

namespace App\Domain\Account\Models;

use App\Domain\Account\Models\ViewModels\AccountPerformanceView;
use App\Domain\Account\Models\ViewModels\AccountTransactionGapStatsView;
use App\Domain\Account\Models\ViewModels\AccountTransactionStatsView;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\PaymentInstruction\Models\RecurringDeposit;
use App\Domain\Shared\Models\HasUuid;
use App\Domain\Shared\Models\SusuScheme;
use App\Domain\Susu\Models\GroupSusu\CorporativeSusu;
use App\Domain\Susu\Models\GroupSusu\DwadieboaSusu;
use App\Domain\Susu\Models\GroupSusu\NkabomNhyiraSusu;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Domain\Susu\Models\IndividualSusu\FlexySusu;
use App\Domain\Susu\Models\IndividualSusu\GoalGetterSusu;
use App\Domain\Transaction\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Random\RandomException;

/**
 * Class Account
 *
 * Represents a Susu savings account within the system.
 *
 * The Account model serves as the central aggregate root for all account-related
 * operations in the SusuBox domain. It encapsulates ownership, financial activity,
 * payout controls, cycle definitions, balances, and product-specific configurations
 * (e.g., Daily Susu, Biz Susu, Goal Getter Susu, etc.).
 *
 * Key Responsibilities:
 * - Manages relationships between customers and their assigned roles via a pivot table.
 * - Tracks financial activity including transactions and recurring deposits.
 * - Maintains a single authoritative balance record.
 * - Controls payout restrictions through account payout locks.
 * - Supports cycle-based savings logic through cycle definitions and cycles.
 * - Associates with a specific Susu scheme.
 * - Exposes derived statistics and performance views for reporting.
 * - Provides account-level utilities such as payout lock checks and unique account number generation.
 *
 * Routing:
 * - Uses `resource_id` as the route key for public-facing identification.
 *
 * Attributes:
 * @property int $id
 * @property string $resource_id
 * @property int $susu_scheme_id
 * @property string $account_name
 * @property string $account_number
 * @property string $account_type
 * @property bool $accepted_terms
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * Relationships:
 * @property-read Collection|AccountCustomer[] $accountCustomers
 * @property-read Collection|Customer[] $customers
 * @property-read Collection|PaymentInstruction[] $paymentInstructions
 * @property-read Collection|RecurringDeposit[] $recurringDeposits
 * @property-read Collection|Transaction[] $transactions
 * @property-read AccountBalance|null $balance
 * @property-read AccountCycleDefinition|null $accountCycleDefinition
 * @property-read Collection|AccountCycle[] $accountCycles
 * @property-read Collection|AccountPayoutLock[] $accountPayoutLocks
 * @property-read AccountPayoutLock|null $activeAccountPayoutLock
 * @property-read DailySusu|null $dailySusu
 * @property-read BizSusu|null $bizSusu
 * @property-read GoalGetterSusu|null $goalGetterSusu
 * @property-read FlexySusu|null $flexySusu
 * @property-read NkabomNhyiraSusu|null $nkabomNhyiraSusu
 * @property-read DwadieboaSusu|null $dwadieboaSusu
 * @property-read CorporativeSusu|null $corporativeSusu
 * @property-read SusuScheme $susuScheme
 * @property-read AccountTransactionStatsView|null $transactionStats
 * @property-read AccountTransactionGapStatsView|null $transactionGapStats
 * @property-read AccountPerformanceView|null $transactionPerformance
 *
 * Computed Helpers:
 * - isAccountPayoutLocked(): Determines whether the account currently has an active payout restriction.
 * - isFirstTransaction(): Checks if the account has not recorded any transactions.
 *
 * Static Utilities:
 * - generateAccountNumber(): Generates a unique account number using a date-based prefix and random suffix, ensuring database-level uniqueness.
 */
final class Account extends Model
{
    use HasUuid;

    protected $guarded = ['id'];

    protected $casts = [
        'accepted_terms' => 'boolean',
    ];

    protected $fillable = [
        'resource_id',
        'susu_scheme_id',
        'account_name',
        'account_number',
        'account_type',
        'accepted_terms',
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
     * @return HasMany
     */
    public function accountCustomers(
    ): HasMany {
        return $this->hasMany(
            related: AccountCustomer::class,
            foreignKey: 'account_id',
        );
    }

    /**
     * @return BelongsToMany
     */
    public function customers(
    ): BelongsToMany {
        return $this->belongsToMany(
            related: Customer::class,
            table: 'account_customers'
        )
            ->withPivot(['role', 'wallet_id'])
            ->withTimestamps();
    }

    /**
     * @return HasMany
     */
    public function paymentInstructions(
    ): HasMany {
        return $this->hasMany(
            related: PaymentInstruction::class,
            foreignKey: 'account_id',
        );
    }

    /**
     * @return HasMany
     */
    public function recurringDeposits(
    ): HasMany {
        return $this->hasMany(
            related: RecurringDeposit::class,
            foreignKey: 'account_id'
        );
    }

    /**
     * @return HasMany
     */
    public function transactions(
    ): HasMany {
        return $this->hasMany(
            related: Transaction::class,
            foreignKey: 'account_id'
        );
    }

    /**
     * @return HasOne
     */
    public function balance(
    ): HasOne {
        return $this->hasOne(
            related: AccountBalance::class,
            foreignKey: 'account_id'
        );
    }

    /**
     * @return HasOne
     */
    public function accountCycleDefinition(
    ): HasOne {
        return $this->hasOne(
            related: AccountCycleDefinition::class,
            foreignKey: 'account_id',
        );
    }

    /**
     * @return HasMany
     */
    public function accountCycles(
    ): HasMany {
        return $this->hasMany(
            related: AccountCycle::class,
            foreignKey: 'account_id',
        );
    }

    /**
     * @return HasMany
     */
    public function accountPayoutLocks(
    ): HasMany {
        return $this->hasMany(
            related: AccountPayoutLock::class,
            foreignKey: 'account_id',
        );
    }

    /**
     * @return HasOne
     */
    public function activeAccountPayoutLock(
    ): HasOne {
        return $this->hasOne(
            related: AccountPayoutLock::class,
            foreignKey: 'account_id',
        )
            ->where('status', 'active')
            ->where('expires_at', '>', now());
    }

    /**
     * @return bool
     */
    public function isAccountPayoutLocked(
    ): bool {
        return $this->activeAccountPayoutLock()->exists();
    }

    /**
     * @return HasOne
     */
    public function dailySusu(
    ): HasOne {
        return $this->hasOne(
            related: DailySusu::class,
            foreignKey: 'account_id',
        );
    }

    /**
     * @return HasOne
     */
    public function bizSusu(
    ): HasOne {
        return $this->hasOne(
            related: BizSusu::class,
            foreignKey: 'account_id',
        );
    }

    /**
     * @return HasOne
     */
    public function goalGetterSusu(
    ): HasOne {
        return $this->hasOne(
            related: GoalGetterSusu::class,
            foreignKey: 'account_id',
        );
    }

    /**
     * @return HasOne
     */
    public function flexySusu(
    ): HasOne {
        return $this->hasOne(
            related: FlexySusu::class,
            foreignKey: 'account_id',
        );
    }

    /**
     * @return HasOne
     */
    public function nkabomNhyiraSusu(
    ): HasOne {
        return $this->hasOne(
            related: NkabomNhyiraSusu::class,
            foreignKey: 'account_id',
        );
    }

    /**
     * @return HasOne
     */
    public function dwadieboaSusu(
    ): HasOne {
        return $this->hasOne(
            related: DwadieboaSusu::class,
            foreignKey: 'account_id',
        );
    }

    /**
     * @return HasOne
     */
    public function corporativeSusu(
    ): HasOne {
        return $this->hasOne(
            related: CorporativeSusu::class,
            foreignKey: 'account_id',
        );
    }

    /**
     * @return BelongsTo
     */
    public function susuScheme(
    ): BelongsTo {
        return $this->belongsTo(
            related: SusuScheme::class,
            foreignKey: 'susu_scheme_id',
        );
    }

    /**
     * @return HasOne
     */
    public function transactionStats(
    ): HasOne {
        return $this->hasOne(
            related: AccountTransactionStatsView::class,
            foreignKey: 'account_id'
        );
    }

    /**
     * @return HasOne
     */
    public function transactionGapStats(
    ): HasOne {
        return $this->hasOne(
            related: AccountTransactionGapStatsView::class,
            foreignKey: 'account_id'
        );
    }

    /**
     * @return HasOne
     */
    public function transactionPerformance(
    ): HasOne {
        return $this->hasOne(
            related: AccountPerformanceView::class,
            foreignKey: 'account_id'
        );
    }

    /**
     * @return bool
     */
    public function isFirstTransaction(
    ): bool {
        return ! $this->transactions()->exists();
    }

    /**
     * @return string
     * @throws RandomException
     */
    public static function generateAccountNumber(
    ): string {
        do {
            $number = 'ACC' . date('Ymd') . str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        } while (self::where('account_number', $number)->exists());

        return $number;
    }
}
