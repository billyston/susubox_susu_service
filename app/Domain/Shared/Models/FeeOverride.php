<?php

declare(strict_types=1);

namespace App\Domain\Shared\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class FeeOverride
 *
 * Represents a contextual override to a predefined fee or charge rule.
 *
 * The FeeOverride model allows modification of an existing FeesAndCharge
 * configuration for a specific overrideable entity (e.g., account, customer,
 * or scheme instance). It supports temporary or permanent adjustments,
 * enabling flexible fee governance and exception handling.
 *
 * Purpose:
 * - Override standard fee rules defined in FeesAndCharge.
 * - Apply custom fee values for specific entities or conditions.
 * - Support time-bound overrides using start and end dates.
 * - Enable activation and deactivation of overrides dynamically.
 * - Record justification for fee adjustments.
 *
 * Routing:
 * - Uses `resource_id` as the route key for public-facing identification.
 *
 * Attributes:
 * @property int $id
 * @property string $resource_id
 * @property string $overrideable
 * @property int $fee_and_charge_id
 * @property string $override_type
 * @property string $value
 * @property Carbon|null $starts_at
 * @property Carbon|null $ends_at
 * @property bool $is_active
 * @property string|null $reason
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * Relationships:
 * @property-read FeesAndCharge $fees
 *
 * Methods:
 * - getRouteKeyName(): string
 *   Returns 'resource_id' for route model binding.
 *
 * Domain Notes:
 * - `override_type` may define whether the override replaces or adjusts the base fee (e.g., fixed, percentage, discount).
 * - Effective period is controlled via `starts_at` and `ends_at`.
 * - Only active overrides within the valid date range should be applied.
 * - Designed to support promotional pricing, negotiated terms, or special customer arrangements.
 */
final class FeeOverride extends Model
{
    use HasUuid;

    protected $guarded = ['id'];

    protected $casts = [
        'value' => 'decimal:4',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected $fillable = [
        'resource_id',
        'overrideable',
        'fee_and_charge_id',
        'override_type',
        'value',
        'starts_at',
        'ends_at',
        'is_active',
        'reason',
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
    public function fees(
    ): BelongsTo {
        return $this->belongsTo(
            related: FeesAndCharge::class,
            foreignKey: 'fee_and_charge_id'
        );
    }
}
