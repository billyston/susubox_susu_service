<?php

declare(strict_types=1);

namespace App\Domain\Shared\Models;

use App\Domain\PaymentInstruction\Models\RecurringDeposit;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Frequency
 *
 * Represents a contribution or repayment frequency configuration within
 * the SusuBox system.
 *
 * The Frequency model defines how often a recurring financial action occurs
 * (e.g., daily, weekly, monthly). It is primarily used by recurring savings
 * or deposit-based schemes such as RecurringDeposit to standardize scheduling
 * behavior across the platform.
 *
 * Purpose:
 * - Define standardized contribution or repayment intervals.
 * - Control which frequencies are available for use via the `is_allowed` flag.
 * - Provide descriptive metadata for system configuration and reporting.
 *
 * Routing:
 * - Uses `resource_id` as the route key for public-facing identification.
 *
 * Attributes:
 * @property int $id
 * @property string $resource_id
 * @property string $name
 * @property string|null $alias
 * @property string|null $code
 * @property string|null $description
 * @property bool $is_allowed
 *
 * Relationships:
 * @property-read Collection|RecurringDeposit[] $recurringDeposits
 *
 * Methods:
 * - getRouteKeyName(): string
 *   Returns 'resource_id' for route model binding.
 *
 * Domain Notes:
 * - The `is_allowed` flag determines whether a frequency option can be selected for new recurring configurations.
 * - Designed to centralize scheduling logic across savings products.
 */
final class Frequency extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_allowed' => 'boolean',
    ];

    protected $fillable = [
        'resource_id',
        'name',
        'alias',
        'code',
        'description',
        'is_allowed',
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
    public function recurringDeposits(
    ): HasMany {
        return $this->hasMany(
            related: RecurringDeposit::class,
            foreignKey: 'frequency_id',
        );
    }
}
