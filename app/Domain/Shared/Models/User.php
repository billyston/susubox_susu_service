<?php

declare(strict_types=1);

namespace App\Domain\Shared\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class User
 *
 * @property string $id
 * @property string $resource_id
 *
 * @method static Builder|User whereResourceId($value)
 *
 * @mixin Eloquent
 */
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
