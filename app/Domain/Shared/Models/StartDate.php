<?php

declare(strict_types=1);

namespace App\Domain\Shared\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class StartDate
 *
 * @property string $id
 * @property string $resource_id
 * @property string $name
 * @property string $code
 * @property int $days
 * @property string|null $description
 *
 * @method static Builder|StartDate whereResourceId($value)
 * @method static Builder|StartDate whereName($value)
 * @method static Builder|StartDate whereCode($value)
 * @method static Builder|StartDate whereDays($value)
 * @method static Builder|StartDate whereDescription($value)
 *
 * @mixin Eloquent
 */
final class StartDate extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [];

    protected $fillable = [
        'resource_id',
        'name',
        'code',
        'days',
        'description',
    ];

    /**
     * @return string
     */
    public function getRouteKeyName(
    ): string {
        return 'resource_id';
    }
}
