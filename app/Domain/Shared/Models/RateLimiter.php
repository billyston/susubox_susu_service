<?php

declare(strict_types=1);

namespace App\Domain\Shared\Models;

use Illuminate\Database\Eloquent\Model;

final class RateLimiter extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'reset_at' => 'datetime',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'key',
        'group',
        'attempts',
        'reset_at',
        'expires_at',
    ];

    /**
     * @return string
     */
    public function getRouteKeyName(
    ): string {
        return 'resource_id';
    }
}
