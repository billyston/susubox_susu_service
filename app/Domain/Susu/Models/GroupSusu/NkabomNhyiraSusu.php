<?php

declare(strict_types=1);

namespace App\Domain\Susu\Models\GroupSusu;

use App\Domain\Account\Models\Account;
use App\Domain\Shared\Models\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class NkabomNhyiraSusu
 *
 * Represents a traditional group-based rotational Susu savings scheme
 * associated with a specific Account.
 *
 * The NkabomNhyiraSusu model defines a collective savings structure
 * where members contribute periodically into a common pool and receive
 * payouts in rotation based on allocated slots. Each member may hold
 * one or more slots depending on configured limits.
 *
 * This model governs participation rules such as slot limits per member,
 * payout automation behavior, and configurable group settings stored
 * within metadata.
 *
 * Key Responsibilities:
 * - Associates the rotational Susu scheme with an Account.
 * - Defines minimum and maximum slot allocations per member.
 * - Controls whether payouts are processed automatically.
 * - Stores flexible operational configuration via metadata.
 *
 * Routing:
 * - Uses `resource_id` as the route key for public-facing identification.
 *
 * Attributes:
 * @property int $id
 * @property string $resource_id
 * @property int $account_id
 * @property int $min_slot_per_member
 * @property int $max_slot_per_member
 * @property bool $auto_payout
 * @property array|null $metadata
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * Relationships:
 * @property-read Account $account
 *
 * Domain Notes:
 * - Each slot represents an entitlement to receive one payout cycle.
 * - Members may acquire multiple slots within configured limits.
 * - When `auto_payout` is enabled, disbursements may be triggered automatically upon cycle completion.
 * - Designed for traditional rotating savings and credit association flows.
 */
final class NkabomNhyiraSusu extends Model
{
    use HasUuid;

    protected $guarded = ['id'];

    protected $casts = [
        'auto_payout' => 'boolean',
        'metadata' => 'array',
    ];

    protected $fillable = [
        'resource_id',
        'account_id',
        'min_slot_per_member',
        'max_slot_per_member',
        'auto_payout',
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
