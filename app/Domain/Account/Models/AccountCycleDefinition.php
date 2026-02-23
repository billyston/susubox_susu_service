<?php

declare(strict_types=1);

namespace App\Domain\Account\Models;

use App\Domain\Shared\Casts\MoneyCasts;
use App\Domain\Shared\Models\HasUuid;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Class AccountCycleDefinition
 *
 * Defines the structural configuration for cycle-based Accounts.
 *
 * The AccountCycleDefinition model acts as the blueprint for how savings
 * cycles operate within a specific account. It determines contribution
 * expectations, payout structure, commission logic, and overall cycle length.
 *
 * Every AccountCycle created for an account is governed by this definition.
 * While AccountCycle represents a runtime instance of a cycle, this model
 * defines the rules and financial parameters that those cycles must follow.
 *
 * Key Responsibilities:
 * - Defines the number of frequencies required per cycle.
 * - Determines commission and payout frequencies.
 * - Specifies expected contribution and payout amounts.
 * - Stores commission amounts applied per cycle.
 * - Acts as the parent configuration for all generated cycles.
 *
 * Financial Configuration:
 * - `expected_cycle_amount` represents the total expected contribution for a full cycle.
 * - `expected_payout_amount` represents the payout amount after commission adjustments.
 * - `commission_amount` represents the system or platform fee applied within the cycle structure.
 *
 * Structural Parameters:
 * - `cycle_length` defines the total duration or number of intervals in a cycle.
 * - `expected_frequencies` defines how many contributions must occur.
 * - `commission_frequencies` determines when commission is applied.
 * - `payout_frequencies` defines when payout eligibility is triggered.
 *
 * Routing:
 * - Uses `resource_id` as the route key for public-facing identification.
 *
 * Attributes:
 * @property int $id
 * @property string $resource_id
 * @property int $account_id
 * @property int $cycle_length
 * @property int $commission_frequencies
 * @property int $payout_frequencies
 * @property int $expected_frequencies
 * @property Money $expected_cycle_amount
 * @property Money $expected_payout_amount
 * @property Money $commission_amount
 * @property string $currency
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * Relationships:
 * @property-read Account $account
 * @property-read Collection|AccountCycle[] $accountCycles
 *
 * Domain Notes:
 * - This model should typically be created once per account and updated only under controlled conditions.
 * - Changes to this definition may impact future cycles but should not retroactively alter completed cycles.
 */
final class AccountCycleDefinition extends Model
{
    use HasUuid;

    protected $guarded = ['id'];

    protected $casts = [
        'expected_cycle_amount' => MoneyCasts::class,
        'expected_payout_amount' => MoneyCasts::class,
        'commission_amount' => MoneyCasts::class,
    ];

    protected $fillable = [
        'resource_id',
        'account_id',
        'cycle_length',
        'commission_frequencies',
        'payout_frequencies',
        'expected_frequencies',
        'expected_cycle_amount',
        'expected_payout_amount',
        'commission_amount',
        'currency',
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
     * @return HasMany
     */
    public function accountCycles(
    ): HasMany {
        return $this->hasMany(
            related: AccountCycle::class,
            foreignKey: 'account_cycle_definition_id',
        );
    }
}
