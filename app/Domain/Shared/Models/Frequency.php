<?php

declare(strict_types=1);

namespace App\Domain\Shared\Models;

use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Domain\Susu\Models\IndividualSusu\DriveToOwnSusu;
use App\Domain\Susu\Models\IndividualSusu\GoalGetterSusu;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Frequency
 *
 * @property string $id
 * @property string $resource_id
 * @property string $name
 * @property string $alias
 * @property string $code
 * @property string|null $description
 * @property bool $is_allowed
 *
 * Relationships:
 * @property Collection<int, DailySusu> $dailySusu
 * @property Collection<int, BizSusu> $bizSusu
 * @property Collection<int, GoalGetterSusu> $goalGetterSusu
 * @property Collection<int, DriveToOwnSusu> $driveToOwnSusu
 *
 * @method static Builder|Frequency whereResourceId($value)
 * @method static Builder|Frequency whereName($value)
 * @method static Builder|Frequency whereAlias($value)
 * @method static Builder|Frequency whereCode($value)
 * @method static Builder|Frequency whereDescription($value)
 * @method static Builder|Frequency whereIsAllowed($value)
 *
 * @mixin Eloquent
 */
final class Frequency extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [
        'is_allowed' => 'boolean',
    ];

    protected $fillable = [
        'resource_id',
        'name',
        'alias',
        'code',
        'description',
        'is_allowed',
    ];

    /**
     * @return string
     */
    public function getRouteKeyName(
    ): string {
        return 'resource_id';
    }

    /**
     * @return HasMany
     */
    public function dailySusu(
    ): HasMany {
        return $this->hasMany(
            related: DailySusu::class,
            foreignKey: 'frequency_id',
        );
    }

    /**
     * @return HasMany
     */
    public function bizSusu(
    ): HasMany {
        return $this->hasMany(
            related: BizSusu::class,
            foreignKey: 'frequency_id',
        );
    }

    /**
     * @return HasMany
     */
    public function goalGetterSusu(
    ): HasMany {
        return $this->hasMany(
            related: GoalGetterSusu::class,
            foreignKey: 'frequency_id',
        );
    }

    /**
     * @return HasMany
     */
    public function driveToOwnSusu(
    ): HasMany {
        return $this->hasMany(
            related: DriveToOwnSusu::class,
            foreignKey: 'frequency_id',
        );
    }
}
