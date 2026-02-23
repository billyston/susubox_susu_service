<?php

declare(strict_types=1);

namespace App\Domain\Susu\Models\IndividualSusu;

use App\Domain\Account\Models\Account;
use App\Domain\Shared\Models\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class BizSusu
 *
 * Represents an individual-based Susu savings scheme tailored for small businesses.
 *
 * The BizSusu model defines a structured savings plan designed to support
 * micro and small-scale business owners in accumulating capital over a period.
 * Unlike group-based rotational models, this structure
 * is individually focused and tied directly to a single Account.
 *
 * It allows small business owners to save toward working capital, inventory
 * purchases, expansion, or other operational needs within a specified
 * start and end date window. The model also supports collateral-backed
 * arrangements where applicable.
 *
 * Key Responsibilities:
 * - Associates an individual business savings plan with an Account.
 * - Defines the savings duration via start and end dates.
 * - Tracks whether the plan is collateralized.
 * - Monitors payout lifecycle status.
 * - Stores flexible business-specific configuration in metadata.
 *
 * Routing:
 * - Uses `resource_id` as the route key for public-facing identification.
 *
 * Attributes:
 * @property int $id
 * @property string $resource_id
 * @property int $account_id
 * @property Carbon|null $start_date
 * @property Carbon|null $end_date
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
 * - Designed for business-focused savings cycles.
 * - Collateralization may influence payout eligibility or credit extensions.
 * - Payouts are typically processed at or after the defined end date, subject to completion rules.
 */
final class BizSusu extends Model
{
    use HasUuid;

    protected $guarded = ['id'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_collateralized' => 'boolean',
        'metadata' => 'array',
    ];

    protected $fillable = [
        'resource_id',
        'account_id',
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
}
