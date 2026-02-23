<?php

declare(strict_types=1);

namespace App\Domain\Susu\Models\IndividualSusu;

use App\Domain\Account\Models\Account;
use App\Domain\Shared\Casts\MoneyCasts;
use App\Domain\Shared\Models\Duration;
use App\Domain\Shared\Models\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class GoalGetterSusu
 *
 * Represents a goal-oriented individual savings plan where a customer sets a
 * specific target amount to be saved over a defined duration. This scheme is
 * designed to help customers achieve financial goals with structured saving
 * and optional collateralization.
 *
 * The GoalGetterSusu model manages contributions toward a target amount,
 * tracks the start and end dates for the savings duration, and records
 * payout eligibility and status.
 *
 * Key Responsibilities:
 * - Associates the goal-oriented savings plan with an Account.
 * - Tracks the target savings amount and the saving duration.
 * - Handles start and end dates for the plan.
 * - Indicates whether the savings are collateralized.
 * - Stores payout status and additional scheme configuration in metadata.
 *
 * Routing:
 * - Uses `resource_id` as the route key for public-facing identification.
 *
 * Attributes:
 * @property int $id
 * @property string $resource_id
 * @property int $account_id
 * @property int $duration_id
 * @property float|int $target_amount
 * @property string $currency
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property bool $is_collateralized
 * @property string|null $payout_status
 * @property array|null $metadata
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * Relationships:
 * @property-read Account $account
 * @property-read Duration $duration
 *
 * Domain Notes:
 * - Suitable for customers saving toward specific goals.
 * - Payout is generally made at the end of the duration or upon achieving the target.
 * - Metadata may include additional rules, reminders, or personalized plan details.
 */
final class GoalGetterSusu extends Model
{
    use HasUuid;

    protected $guarded = ['id'];

    protected $casts = [
        'target_amount' => MoneyCasts::class,
        'start_date' => 'date',
        'end_date' => 'date',
        'is_collateralized' => 'boolean',
        'metadata' => 'array',
    ];

    protected $fillable = [
        'resource_id',
        'account_id',
        'duration_id',
        'target_amount',
        'currency',
        'start_date',
        'end_date',
        'is_collateralized',
        'payout_status',
        'metadata',
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
     * @return BelongsTo
     */
    public function duration(
    ): BelongsTo {
        return $this->belongsTo(
            related: Duration::class,
            foreignKey: 'duration_id'
        );
    }
}
