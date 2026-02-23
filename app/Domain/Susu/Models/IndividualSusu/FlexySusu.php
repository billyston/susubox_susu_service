<?php

declare(strict_types=1);

namespace App\Domain\Susu\Models\IndividualSusu;

use App\Domain\Account\Models\Account;
use App\Domain\Shared\Casts\MoneyCasts;
use App\Domain\Shared\Models\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class FlexySusu
 *
 * Represents a flexible individual-based savings scheme where customers
 * make direct savings into their account without a fixed cycle.
 *
 * The FlexySusu model allows account holders to deposit funds at their
 * convenience while optionally marking the savings as collateralized.
 * This scheme is designed for customers seeking flexibility in contribution
 * timing and amount, while maintaining the benefits of structured record-keeping.
 *
 * Key Responsibilities:
 * - Associates the flexible savings plan with a specific Account.
 * - Tracks the initial deposit and ongoing contributions.
 * - Indicates whether the savings are collateralized.
 * - Maintains payout status and additional configuration in metadata.
 *
 * Routing:
 * - Uses `resource_id` as the route key for public-facing identification.
 *
 * Attributes:
 * @property int $id
 * @property string $resource_id
 * @property int $account_id
 * @property float|int $initial_deposit
 * @property string $currency
 * @property bool $is_collateralized
 * @property string|null $payout_status
 * @property array|null $metadata
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * Relationships:
 * @property-read Account $account
 *
 * Domain Notes:
 * - Designed for customers who prefer flexible contribution schedules.
 * - Metadata can store additional scheme rules, preferences, or notes.
 * - Payouts can be triggered based on account balance or manual request.
 */
final class FlexySusu extends Model
{
    use HasUuid;

    protected $guarded = ['id'];

    protected $casts = [
        'initial_deposit' => MoneyCasts::class,
        'is_collateralized' => 'boolean',
        'metadata' => 'array',
    ];

    protected $fillable = [
        'resource_id',
        'account_id',
        'initial_deposit',
        'currency',
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
}
