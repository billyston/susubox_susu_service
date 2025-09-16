<?php

declare(strict_types=1);

namespace Domain\Shared\Models;

use Illuminate\Database\Eloquent\Model;

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
}
