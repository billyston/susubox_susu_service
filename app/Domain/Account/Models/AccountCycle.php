<?php

declare(strict_types=1);

namespace App\Domain\Account\Models;

use App\Domain\Shared\Casts\MoneyCasts;
use App\Domain\Shared\Models\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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
