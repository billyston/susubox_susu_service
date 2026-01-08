<?php

declare(strict_types=1);

namespace App\Domain\Account\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * Class AccountPause
 *
 * Represents a temporary pause applied to a pauseable model
 * (e.g. Account, Wallet, or any other pauseable entity).
 *
 * @property int $id
 * @property string $resource_id
 *
 * @property string $pauseable_type
 * @property int $pauseable_id
 *
 * @property Carbon|null $paused_at
 * @property Carbon|null $resumed_at
 *
 * @property bool|null $accepted_terms
 * @property string $status
 *
 * @property Model|MorphTo $pauseable
 *
 * @method static Builder|AccountPause whereResourceId(string $resourceId)
 * @method static Builder|AccountPause whereStatus(string $status)
 * @method static Builder|AccountPause wherePausedAt($value)
 * @method static Builder|AccountPause whereResumedAt($value)
 *
 * @mixin Eloquent
 */
final class AccountPause extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'paused_at' => 'datetime',
        'resumed_at' => 'datetime',
    ];

    protected $fillable = [
        'resource_id',
        'pauseable',
        'paused_at',
        'resumed_at',
        'accepted_terms',
        'status',
    ];

    public function getRouteKeyName(
    ): string {
        return 'resource_id';
    }

    public function pauseable(
    ): MorphTo {
        return $this->morphTo();
    }
}
