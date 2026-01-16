<?php

declare(strict_types=1);

namespace App\Domain\Account\Models;

use App\Domain\Shared\Models\HasUuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class AccountAutoDebit
 *
 * Represents a request or change to the auto-debit state of a debit-enabled entity.
 * Tracks the requested state change, who initiated it, and when it becomes effective.
 *
 * @property int $id
 * @property string $resource_id
 *
 * @property string $debitable_type
 * @property int $debitable_id
 *
 * @property string $action
 * @property bool $from_state
 * @property bool $to_state
 *
 * @property Carbon|null $requested_at
 * @property Carbon|null $effective_at
 *
 * @property string|null $initiator
 *
 * @property-read Model $debitable
 */
final class AccountAutoDebit extends Model
{
    use HasUuid;

    protected $guarded = ['id'];

    protected $casts = [
        'from_state' => 'boolean',
        'to_state' => 'boolean',
        'requested_at' => 'datetime',
        'effective_at' => 'datetime',
    ];

    protected $fillable = [
        'resource_id',
        'debitable_type',
        'action',
        'from_state',
        'to_state',
        'requested_at',
        'effective_at',
        'initiator',
    ];

    /**
     * @return string
     */
    public function getRouteKeyName(
    ): string {
        return 'resource_id';
    }

    /**
     * @return MorphTo
     */
    public function debitable(
    ): MorphTo {
        return $this->morphTo();
    }
}
