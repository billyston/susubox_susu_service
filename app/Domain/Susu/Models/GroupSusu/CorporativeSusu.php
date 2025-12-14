<?php

declare(strict_types=1);

namespace App\Domain\Susu\Models\GroupSusu;

use Illuminate\Database\Eloquent\Model;

final class CorporativeSusu extends Model
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
}
