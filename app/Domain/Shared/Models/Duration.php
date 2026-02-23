<?php

declare(strict_types=1);

namespace App\Domain\Shared\Models;

use App\Domain\Susu\Models\IndividualSusu\GoalGetterSusu;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Duration
 *
 * Represents a predefined time duration configuration used within the SusuBox system.
 *
 * The Duration model defines standardized savings, pause, lock periods (e.g., 30 days,
 * 90 days, 180 days, etc.) that can be attached to payment lock, debit pause and savings
 * schemes, such as GoalGetterSusu. It provides a structured way to manage time-bound financial
 * products by defining the number of days, status, and identifying code.
 *
 * Purpose:
 * - Define reusable time durations.
 * - Standardize duration logic across time-bound schemes.
 * - Enable activation/deactivation of specific duration options.
 *
 * Routing:
 * - Uses `resource_id` as the route key for public-facing identification.
 *
 * Attributes:
 * @property int $id
 * @property string $resource_id
 * @property string $name
 * @property string|null $code
 * @property int $days
 * @property string|null $status
 *
 * Relationships:
 * @property-read Collection|GoalGetterSusu[] $goalGetterSusu
 *
 * Methods:
 * - getRouteKeyName(): string
 *   Returns 'resource_id' for route model binding.
 *
 * Domain Notes:
 * - The `days` attribute determines the length of a savings plan.
 * - Status may be used to control availability (e.g., active/inactive).
 * - Designed to ensure consistency across time-based savings products.
 */
final class Duration extends Model
{
    protected $guarded = ['id'];

    protected $casts = [];

    protected $fillable = [
        'resource_id',
        'name',
        'code',
        'days',
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
    public function goalGetterSusu(
    ): HasMany {
        return $this->hasMany(
            related: GoalGetterSusu::class,
            foreignKey: 'duration_id'
        );
    }
}
