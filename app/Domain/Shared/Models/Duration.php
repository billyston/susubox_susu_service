<?php

declare(strict_types=1);

namespace App\Domain\Shared\Models;

use App\Domain\Susu\Models\GoalGetterSusu;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Duration extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [];

    protected $fillable = [
        'resource_id',
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
