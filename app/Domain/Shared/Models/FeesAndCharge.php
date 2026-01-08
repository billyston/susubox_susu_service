<?php

declare(strict_types=1);

namespace App\Domain\Shared\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Class FeesAndCharge
 *
 * @property int $id
 * @property string $resource_id
 * @property int $susu_scheme_id
 * @property string $event
 * @property string $calculation_type
 * @property float $value
 * @property bool $is_active
 * @property Carbon|null $effective_from
 * @property Carbon|null $effective_to
 *
 * @property SusuScheme $scheme
 * @property Collection|FeeOverride[] $overrides
 *
 * @method static Builder|FeesAndCharge whereResourceId(string $resourceId)
 * @method static Builder|FeesAndCharge whereIsActive(bool $active)
 * @method static Builder|FeesAndCharge active()
 *
 * @mixin Eloquent
 */
final class FeesAndCharge extends Model
{
    use HasUuid;

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [
        'value' => 'decimal:4',
        'is_active' => 'boolean',
        'effective_from' => 'datetime',
        'effective_to' => 'datetime',
    ];

    protected $fillable = [
        'resource_id',
        'susu_scheme_id',
        'event',
        'calculation_type',
        'value',
        'is_active',
        'effective_from',
        'effective_to',
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
    public function scheme(
    ): BelongsTo {
        return $this->belongsTo(
            related: SusuScheme::class,
            foreignKey: 'susu_scheme_id',
        );
    }

    /**
     * @return HasMany
     */
    public function overrides(
    ): HasMany {
        return $this->hasMany(
            related: FeeOverride::class,
            foreignKey: 'fee_override_id',
        );
    }
}
