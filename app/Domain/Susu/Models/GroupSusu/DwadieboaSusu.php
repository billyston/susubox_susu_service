<?php

declare(strict_types=1);

namespace App\Domain\Susu\Models\GroupSusu;

use App\Domain\Account\Models\Account;
use App\Domain\Shared\Models\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class DwadieboaSusu
 *
 * Represents a Dwadieboa (item-based reward) group savings structure
 * linked to a specific Account.
 *
 * The DwadieboaSusu model defines a cooperative savings scheme where
 * individuals contribute toward a predefined savings package that results
 * in a tangible item or bundled reward upon completion, rather than a
 * direct monetary payout.
 *
 * Unlike rotational or cycle-based payout models, this structure is
 * goal-oriented: each participant saves toward qualifying for a specific
 * package (e.g., goods, appliances, bundled benefits), making it
 * contribution-driven and reward-based.
 *
 * Key Responsibilities:
 * - Associates the Dwadieboa savings scheme with an Account.
 * - Stores configurable package details and operational rules via metadata.
 * - Serves as the root configuration entity for item-reward savings flows.
 *
 * Routing:
 * - Uses `resource_id` as the route key for public-facing identification.
 *
 * Attributes:
 * @property int $id
 * @property string $resource_id
 * @property int $account_id
 * @property array|null $metadata
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * Relationships:
 * @property-read Account $account
 *
 * Domain Notes:
 * - Designed to support package-based contribution logic.
 * - Reward fulfillment is typically triggered when savings reach a defined threshold or milestone.
 * - Metadata may include package details, pricing structure, contribution rules, and eligibility conditions.
 */
final class DwadieboaSusu extends Model
{
    use HasUuid;

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
