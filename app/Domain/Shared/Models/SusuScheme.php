<?php

declare(strict_types=1);

namespace App\Domain\Shared\Models;

use Illuminate\Database\Eloquent\Model;

final class SusuScheme extends Model
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
        'status',
    ];

    public function getRouteKeyName(
    ): string {
        return 'resource_id';
    }
}
