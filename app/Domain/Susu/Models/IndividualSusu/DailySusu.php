<?php

declare(strict_types=1);

namespace App\Domain\Susu\Models\IndividualSusu;

use App\Domain\Account\Models\Account;
use App\Domain\Shared\Models\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class DailySusu
 *
 * Represents a traditional individual-based Susu savings plan with a 31-day cycle,
 * where contributions are collected daily and savings become eligible for payout
 * at the end of each cycle. A new cycle starts automatically after the previous cycle ends.
 *
 * The DailySusu model manages the lifecycle of an individual’s daily savings,
 * including start and end dates, payout eligibility, automatic rollover into
 * a new cycle, optional collateralization, and additional configuration stored in metadata.
 *
 * Key Responsibilities:
 * - Associates the daily Susu plan with an Account.
 * - Tracks the 31-day savings cycle duration.
 * - Handles automatic rollover into a new cycle after payout.
 * - Indicates whether the savings plan is collateralized.
 * - Manages payout processing status and optional automatic payout.
 * - Stores flexible configuration in the metadata field.
 *
 * Routing:
 * - Uses `resource_id` as the route key for public-facing identification.
 *
 * Attributes:
 * @property int $id
 * @property string $resource_id
 * @property int $account_id
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property bool $is_collateralized
 * @property bool $auto_payout
 * @property string|null $payout_status
 * @property array|null $metadata
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * Relationships:
 * @property-read Account $account
 *
 * Domain Notes:
 * - Designed for individual daily contribution savings schemes.
 * - Each 31-day cycle accrues savings that become eligible for payout.
 * - Automatic cycle rollover ensures continuous savings without manual intervention.
 * - Collateralization and auto-payout options enable integration with credit or automated disbursement flows.
 */
final class DailySusu extends Model
{
    use HasUuid;

    protected $guarded = ['id'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_collateralized' => 'boolean',
        'auto_payout' => 'boolean',
        'metadata' => 'array',
    ];

    protected $fillable = [
        'resource_id',
        'account_id',
        'start_date',
        'end_date',
        'is_collateralized',
        'auto_payout',
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
