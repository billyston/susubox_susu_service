<?php

declare(strict_types=1);

namespace App\Domain\Shared\Models;

use Illuminate\Database\Eloquent\Model;

final class CommissionAndCharge extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [];

    protected $fillable = [
        'resource_id',
    ];

    public function getRouteKeyName(
    ): string {
        return 'resource_id';
    }
}
