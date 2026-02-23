<?php

declare(strict_types=1);

namespace App\Domain\PaymentInstruction\Models;

use App\Domain\Shared\Models\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class RecurringDepositPause
 *
 * Represents a temporary suspension of a RecurringDeposit.
 *
 * The RecurringDepositPause model allows a customer to pause a recurring
 * deposit for a specified period without cancelling the deposit entirely.
 * This feature provides flexibility for customers who wish to temporarily
 * withhold contributions due to financial constraints or other reasons.
 *
 * Key Responsibilities:
 * - Records when a recurring deposit is paused (`paused_at`) and when the pause expires (`expires_at`).
 * - Tracks whether the customer accepted terms associated with the pause.
 * - Maintains the lifecycle status of the pause (e.g., active, expired, cancelled).
 * - Connects the pause to the associated RecurringDeposit.
 *
 * Behavioral Notes:
 * - While an active pause exists, contributions from the recurring deposit should not be processed.
 * - Expiration of the pause does not cancel the recurring deposit; it simply resumes scheduled contributions.
 * - Only one active pause should typically exist per recurring deposit at a time.
 *
 * Routing:
 * - Uses `resource_id` as the route key for public-facing identification.
 *
 * Attributes:
 * @property int $id
 * @property string $resource_id
 * @property int $recurring_deposit_id
 * @property Carbon|null $paused_at
 * @property Carbon|null $expires_at
 * @property bool $accepted_terms
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * Relationships:
 * @property-read RecurringDeposit $recurringDeposit
 *
 * Domain Notes:
 * - This model supports customer discipline by providing controlled suspension options.
 * - Pause validation should check both `status` and `expires_at` to determine if contributions are blocked.
 */
final class RecurringDepositPause extends Model
{
    use HasUuid;

    protected $guarded = ['id'];

    protected $casts = [
        'paused_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    protected $fillable = [
        'resource_id',
        'recurring_deposit_id',
        'paused_at',
        'expires_at',
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
     * @return BelongsTo
     */
    public function recurringDeposit(
    ): BelongsTo {
        return $this->belongsTo(
            related: RecurringDeposit::class,
            foreignKey: 'recurring_deposit_id',
        );
    }
}
