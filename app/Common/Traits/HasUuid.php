<?php

declare(strict_types=1);

namespace App\Common\Traits;

use Illuminate\Support\Str;

trait HasUuid
{
    public static function bootHasResourceUuid(
    ): void {
        static::creating(function ($model): void {
            if (empty($model->resource_id)) {
                $model->resource_id = (string) Str::uuid();
            }
        });
    }
}
