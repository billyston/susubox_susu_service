<?php

declare(strict_types=1);

namespace App\Domain\Shared\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasUuid
{
    public static function bootHasUuid(): void
    {
        /** @phpstan-ignore-next-line */
        static::creating(callback: fn (Model $model) => $model->resource_id = Str::uuid()->toString());
    }
}
