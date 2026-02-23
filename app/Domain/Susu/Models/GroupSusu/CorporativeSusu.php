<?php

declare(strict_types=1);

namespace App\Domain\Susu\Models\GroupSusu;

use App\Domain\Account\Models\Account;
use App\Domain\Shared\Models\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class CorporativeSusu
 *
 * Represents a corporative (group-based) Susu configuration tied to a specific Account.
 *
 * The CorporativeSusu model defines the structural and configuration layer
 * for a cooperative savings scheme (Susu) operating under an Account.
 * It is typically used to manage group-based contribution systems where
 * multiple members contribute periodically and receive payouts in rotation
 * or according to defined rules.
 *
 * This model primarily serves as a container for corporative-level
 * configuration and metadata, enabling flexible customization of
 * contribution rules, participation structure, and operational settings.
 *
 * Key Responsibilities:
 * - Associates a corporative savings structure with an Account.
 * - Stores flexible configuration data via the `metadata` attribute.
 * - Serves as the root entity for corporative Susu operations.
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
 * - Designed for extensibility via the `metadata` field.
 * - Supports group-based savings cycles, member coordination, and corporative payout logic.
 */
final class CorporativeSusu extends Model
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
