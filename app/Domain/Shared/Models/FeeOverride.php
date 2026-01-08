<?php

declare(strict_types=1);

namespace App\Domain\Shared\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * Class FeeOverride
 *
 * @property int $id
 * @property string $resource_id
 *
 * @property int $fee_and_charge_id
 * @property string $override_type
 *
 * @property float $value
 * @property Carbon|null $starts_at
 * @property Carbon|null $ends_at
 * @property bool $is_active
 *
 * @property string|null $reason
 *
 * @property FeesAndCharge $feeAndCharge
 * @property Model|MorphTo $overrideable
 *
 * @method static Builder|FeeOverride whereResourceId(string $resourceId)
 * @method static Builder|FeeOverride whereOverrideType(string $type)
 * @method static Builder|FeeOverride whereIsActive(bool $active)
 * @method static Builder|FeeOverride active()
 *
 * @mixin Eloquent
 */
final class FeeOverride extends Model
{
    use HasUuid;

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [
        'value' => 'decimal:4',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected $fillable = [
        'resource_id',
        'overrideable',
        'fee_and_charge_id',
        'override_type',
        'value',
        'starts_at',
        'ends_at',
        'is_active',
        'reason',
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
    public function fees(
    ): BelongsTo {
        return $this->belongsTo(
            related: FeesAndCharge::class,
            foreignKey: 'fee_and_charge_id'
        );
    }

    /**
     * @return MorphTo
     */
    public function overrideable(
    ): MorphTo {
        return $this->morphTo();
    }
}
