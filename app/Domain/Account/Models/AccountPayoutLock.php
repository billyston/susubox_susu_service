<?php

declare(strict_types=1);

namespace App\Domain\Account\Models;

use App\Domain\Shared\Models\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class AccountPayoutLock
 *
 * Represents a customer-initiated payout restriction on an Account.
 *
 * The AccountPayoutLock model is used to temporarily prevent withdrawals
 * or settlements from an account. Unlike system-enforced restrictions
 * (e.g., compliance, fraud, or risk controls), this lock is primarily
 * initiated by the customer as a discipline mechanism to prevent
 * premature access to funds.
 *
 * It supports structured savings behavior by allowing customers to:
 * - Voluntarily restrict payouts for a defined period.
 * - Commit to a savings goal with a time-bound lock.
 * - Accept specific terms before activating the restriction.
 *
 * Key Responsibilities:
 * - Records when the payout restriction was activated.
 * - Defines the expiration date of the lock.
 * - Tracks whether the customer accepted the lock terms.
 * - Maintains the lifecycle status of the lock (e.g., active, expired, cancelled).
 *
 * Behavioral Notes:
 * - While active and not expired, payouts from the associated account should be programmatically blocked.
 * - Expiration does not automatically imply settlement; it simply removes the voluntary restriction.
 * - Only one active payout lock should typically exist per account at a given time.
 *
 * Routing:
 * - Uses `resource_id` as the route key for public-facing identification.
 *
 * Attributes:
 * @property int $id
 * @property string $resource_id
 * @property int $account_id
 * @property Carbon|null $locked_at
 * @property Carbon|null $expires_at
 * @property bool $accepted_terms
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * Relationships:
 * @property-read Account $account
 *
 * Domain Notes:
 * - This model reinforces savings discipline rather than enforcing regulatory or system-level restrictions.
 * - Lock validation logic should check both `status` and `expires_at` before determining whether payouts are blocked.
 */
final class AccountPayoutLock extends Model
{
    use HasUuid;

    protected $guarded = ['id'];

    protected $casts = [
        'locked_at' => 'datetime',
        'expires_at' => 'datetime',
        'accepted_terms' => 'boolean',
    ];

    protected $fillable = [
        'resource_id',
        'account_id',
        'locked_at',
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
    public function account(
    ): BelongsTo {
        return $this->belongsTo(
            related: Account::class,
            foreignKey: 'account_id',
        );
    }
}
