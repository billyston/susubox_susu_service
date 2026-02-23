<?php

declare(strict_types=1);

namespace App\Domain\Shared\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Class FeesAndCharge
 *
 * Represents a configurable fee or charge rule applied to a specific Susu scheme.
 *
 * The FeesAndCharge model defines how and when fees are calculated within
 * the system. It supports different calculation types (e.g., percentage-based
 * or fixed amount), event-based triggers (e.g., deposit, withdrawal, payout),
 * and effective date ranges for controlled activation.
 *
 * This model enables dynamic pricing and fee management across various
 * Susu schemes, ensuring flexibility and proper financial governance.
 *
 * Purpose:
 * - Define fee rules tied to a SusuScheme.
 * - Specify calculation type and fee value.
 * - Control activation using `is_active` and effective date ranges.
 * - Support scheme-level overrides through related FeeOverride records.
 *
 * Routing:
 * - Uses `resource_id` as the route key for public-facing identification.
 *
 * Attributes:
 * @property int $id
 * @property string $resource_id
 * @property int $susu_scheme_id
 * @property string $event
 * @property string $calculation_type
 * @property string $value
 * @property bool $is_active
 * @property Carbon|null $effective_from
 * @property Carbon|null $effective_to
 *
 * Relationships:
 * @property-read SusuScheme $susuScheme
 * @property-read Collection|FeeOverride[] $feeOverrides
 *
 * Methods:
 * - getRouteKeyName(): string
 *   Returns 'resource_id' for route model binding.
 *
 * Domain Notes:
 * - `calculation_type` may define whether the value is fixed or percentage-based.
 * - `event` determines when the fee is applied in the transaction lifecycle.
 * - Effective date fields allow versioned fee configurations over time.
 * - Overrides allow granular adjustments at account or user level.
 */
final class FeesAndCharge extends Model
{
    use HasUuid;

    protected $guarded = ['id'];

    protected $casts = [
        'value' => 'decimal:4',
        'is_active' => 'boolean',
        'effective_from' => 'datetime',
        'effective_to' => 'datetime',
    ];

    protected $fillable = [
        'resource_id',
        'susu_scheme_id',
        'event',
        'calculation_type',
        'value',
        'is_active',
        'effective_from',
        'effective_to',
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
    public function susuScheme(
    ): BelongsTo {
        return $this->belongsTo(
            related: SusuScheme::class,
            foreignKey: 'susu_scheme_id',
        );
    }

    /**
     * @return HasMany
     */
    public function feeOverrides(
    ): HasMany {
        return $this->hasMany(
            related: FeeOverride::class,
            foreignKey: 'fee_override_id',
        );
    }
}
