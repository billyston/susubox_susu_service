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
 * Class AccountCycle
 *
 * Represents a single savings cycle instance for a cycle-based Account.
 *
 * The AccountCycle model encapsulates one full contribution round within
 * a cycle-driven Susu account. It tracks the expected contribution amount,
 * frequency requirements, actual contributions made, and lifecycle milestones
 * such as start, completion, and settlement.
 *
 * A cycle is created based on an AccountCycleDefinition and progresses
 * through defined states (e.g., pending, active, completed, settled).
 * Each cycle aggregates individual AccountCycleEntry records that represent
 * per-customer contributions within that cycle.
 *
 * Key Responsibilities:
 * - Tracks the expected and actual monetary contributions for the cycle.
 * - Enforces contribution frequency expectations.
 * - Maintains lifecycle timestamps (started, completed, settled).
 * - Associates with a specific account and its cycle definition.
 * - Aggregates all contribution entries made within the cycle.
 *
 * Financial Integrity Notes:
 * - `expected_amount` defines the total required contribution for the cycle.
 * - `contributed_amount` reflects the sum of all successful cycle entries.
 * - Completion should only occur when `completed_frequencies` and
 *   `contributed_amount` meet defined expectations.
 * - Settlement represents payout or final disbursement execution.
 *
 * Routing:
 * - Uses `resource_id` as the route key for public-facing identification.
 *
 * Attributes:
 * @property int $id
 * @property string $resource_id
 * @property int $account_id
 * @property int $account_cycle_definition_id
 * @property int $cycle_number
 * @property int $expected_frequencies
 * @property int $completed_frequencies
 * @property Money $expected_amount
 * @property Money $contributed_amount
 * @property string $currency
 * @property Carbon|null $started_at
 * @property Carbon|null $completed_at
 * @property Carbon|null $settled_at
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * Relationships:
 * @property-read Account $account
 * @property-read AccountCycleDefinition $accountCycleDefinition
 * @property-read Collection|AccountCycleEntry[] $accountCycleEntries
 *
 * Domain Notes:
 * - Cycles are sequential and identified by `cycle_number`.
 * - This model is central to implementing rotating savings logic,
 *   cooperative contributions, and structured payout flows.
 */
final class AccountCycle extends Model
{
    use HasUuid;

    protected $guarded = ['id'];

    protected $casts = [
        'expected_amount' => MoneyCasts::class,
        'contributed_amount' => MoneyCasts::class,
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'settled_at' => 'datetime',
    ];

    protected $fillable = [
        'resource_id',
        'account_id',
        'account_cycle_definition_id',
        'cycle_number',
        'expected_frequencies',
        'completed_frequencies',
        'expected_amount',
        'contributed_amount',
        'currency',
        'started_at',
        'completed_at',
        'settled_at',
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
    public function accountCycleDefinition(
    ): BelongsTo {
        return $this->belongsTo(
            related: AccountCycleDefinition::class,
            foreignKey: 'account_cycle_definition_id'
        );
    }

    /**
     * @return HasMany
     */
    public function accountCycleEntries(
    ): HasMany {
        return $this->hasMany(
            related: AccountCycleEntry::class,
            foreignKey: 'account_cycle_id',
        );
    }
}
