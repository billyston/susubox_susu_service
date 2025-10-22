<?php

declare(strict_types=1);

namespace App\Domain\Shared\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

final class User extends Authenticatable implements JWTSubject
{
    protected $guarded = ['id'];

    protected $casts = [];

    protected $fillable = [
        'resource_id',
    ];

    public function getRouteKeyName(
    ): string {
        return 'resource_id';
    }

    public function getJWTIdentifier(
    ): mixed {
        return $this->getKey();
    }

    public function getJWTCustomClaims(
    ): array {
        return [];
    }
}
