<?php

declare(strict_types=1);

namespace Domain\Shared\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function getRouteKeyName(
    ): string {
        return 'resource_id';
    }
}
