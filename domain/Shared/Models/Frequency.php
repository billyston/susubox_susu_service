<?php

declare(strict_types=1);

namespace Domain\Shared\Models;

use Illuminate\Database\Eloquent\Model;

final class Frequency extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [];

    protected $fillable = [
        'resource_id',
        'name',
        'alias',
        'code',
        'description',
        'is_allowed',
    ];

    public function getRouteKeyName(
    ): string {
        return 'resource_id';
    }
}
