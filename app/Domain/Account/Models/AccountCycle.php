<?php

declare(strict_types=1);

namespace App\Domain\Account\Models;

use App\Domain\Shared\Casts\MoneyCasts;
use App\Domain\Shared\Models\HasUuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class AccountCycle
 *
 * Represents a single contribution cycle for an account.
 * A cycle tracks expected vs completed frequencies, monetary progress,
 * and lifecycle timestamps (started, completed, settled).
 *
 * Cycles are polymorphically linked to cycleable entities
 * (e.g. DailySusu, BizSusu, GoalGetterSusu, etc.).
 *
 * @property int $id
 * @property string $resource_id
 *
 * @property int $account_id
 * @property string $cycleable_type
 * @property int $cycleable_id
 *
 * @property int $cycle_number
 * @property int $expected_frequencies
 * @property int $completed_frequencies
 *
 * @property mixed $expected_amount
 * @property mixed $contributed_amount
 * @property string $currency
 *
 * @property Carbon|null $started_at
 * @property Carbon|null $completed_at
 * @property Carbon|null $settled_at
 *
 * @property string $status
 *
 * @property-read Account $account
 * @property-read Model $cycleable
 * @property-read Collection<int, AccountCycleEntry> $entries
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
        'cycleable_type',
        'cycleable_id',
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
     * @return MorphTo
     */
    public function cycleable(
    ): MorphTo {
        return $this->morphTo();
    }

    /**
     * @return HasMany
     */
    public function entries(
    ): HasMany {
        return $this->hasMany(
            related: AccountCycleEntry::class,
            foreignKey: 'account_cycle_id',
        );
    }

    /**
     * @return int
     */
    public function remainingFrequencies(
    ): int {
        return max(
            0,
            $this->expected_frequencies - $this->completed_frequencies
        );
    }

    /**
     * @return bool
     */
    public function isComplete(
    ): bool {
        return $this->completed_frequencies >= $this->expected_frequencies;
    }
}
