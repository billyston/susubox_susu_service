<?php

declare(strict_types=1);

namespace App\Domain\Shared\Models;

use App\Domain\Susu\Models\IndividualSusu\GoalGetterSusu;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Duration
 *
 * @property string $id
 * @property string $resource_id
 * @property string $name
 * @property string $code
 * @property int $days
 * @property string $status
 *
 * Relationships:
 * @property Collection<int, GoalGetterSusu> $goal
 *
 * @method static Builder|Duration whereResourceId($value)
 * @method static Builder|Duration whereName($value)
 * @method static Builder|Duration whereCode($value)
 * @method static Builder|Duration whereDays($value)
 * @method static Builder|Duration whereStatus($value)
 *
 * @mixin Eloquent
 */
final class Duration extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [];

    protected $fillable = [
        'resource_id',
        'name',
        'code',
        'days',
        'status',
    ];

    public function getRouteKeyName(
    ): string {
        return 'resource_id';
    }

    public function goal(
    ): HasMany {
        return $this->hasMany(
            related: GoalGetterSusu::class,
            foreignKey: 'duration_id'
        );
    }
}
