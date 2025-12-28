<?php

declare(strict_types=1);

namespace App\Domain\Account\Models;

use App\Domain\Shared\Models\HasUuid;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * Class AccountLock
 *
 * @property int $id
 * @property string $resource_id
 *
 * @property string $lockable_type
 * @property int $lockable_id
 *
 * @property Carbon|null $locked_at
 * @property Carbon|null $unlocked_at
 *
 * @property bool|null $accepted_terms
 * @property string $status
 *
 * @property Model|MorphTo $lockable
 *
 * @method static Builder|AccountLock whereResourceId(string $resourceId)
 * @method static Builder|AccountLock whereStatus(string $status)
 * @method static Builder|AccountLock whereLockedAt($value)
 * @method static Builder|AccountLock whereUnlockedAt($value)
 *
 * @mixin Eloquent
 */
final class AccountLock extends Model
{
    use HasUuid;

    protected $guarded = ['id'];

    protected $casts = [
        'locked_at' => 'datetime',
        'unlocked_at' => 'datetime',
    ];

    protected $fillable = [
        'resource_id',
        'lockable',
        'locked_at',
        'unlocked_at',
        'accepted_terms',
        'status',
    ];

    public function getRouteKeyName(
    ): string {
        return 'resource_id';
    }

    public function lockable(
    ): MorphTo {
        return $this->morphTo();
    }
}
