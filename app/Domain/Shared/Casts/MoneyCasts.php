<?php

declare(strict_types=1);

namespace App\Domain\Shared\Casts;

use Brick\Money\Money;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class MoneyCasts implements CastsAttributes
{
    public function get(
        Model $model,
        string $key,
        mixed $value,
        array $attributes
    ): Money {
        return Money::ofMinor(
            $value,
            $attributes['currency'],
        );
    }

    public function set(
        Model $model,
        string $key,
        mixed $value,
        array $attributes,
    ): ?array {
        if ($value instanceof Money) {
            return [
                $key => $value->getMinorAmount()->toInt(),
            ];
        }

        return null;
    }
}
