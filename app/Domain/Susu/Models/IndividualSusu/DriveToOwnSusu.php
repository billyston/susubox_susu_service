<?php

declare(strict_types=1);

namespace App\Domain\Susu\Models\IndividualSusu;

use App\Domain\Account\Models\Account;
use App\Domain\Shared\Models\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class DriveToOwnSusu
 *
 * Represents the Drive-to-Own Susu scheme, one of SusuBox’s flagship
 * savings initiatives where subscribers contribute daily towards
 * purchasing a vehicle for commercial purposes.
 *
 * The DriveToOwnSusu model manages a subscriber’s contributions toward
 * acquiring a vehicle. Subscribers are debited every day until the
 * full amount required to own the vehicle is completed, at which point
 * ownership is transferred to the subscriber.
 *
 * Key Responsibilities:
 * - Associates the Drive-to-Own scheme with a specific Account.
 * - Tracks daily contributions toward vehicle ownership.
 * - Stores flexible configuration and scheme details in metadata.
 * - Supports the operational workflow of daily automated debits.
 *
 * Routing:
 * - Uses `resource_id` as the route key for public-facing identification.
 *
 * Attributes:
 * @property int $id
 * @property string $resource_id
 * @property int $account_id
 * @property array|null $metadata
 *
 * Relationships:
 * @property-read Account $account
 *
 * Domain Notes:
 * - Designed for commercial vehicle acquisition schemes.
 * - Daily contributions continue until the total purchase amount is reached.
 * - Metadata may include vehicle details, contribution schedules, and special conditions.
 * - No timestamps are maintained, as the scheme focuses on continuous contribution flow.
 */
final class DriveToOwnSusu extends Model
{
    use HasUuid;

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [
        'metadata' => 'array',
    ];

    protected $fillable = [
        'resource_id',
        'account_id',
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
