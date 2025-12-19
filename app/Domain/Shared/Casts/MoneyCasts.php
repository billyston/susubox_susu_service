<?php

declare(strict_types=1);

namespace App\Domain\Shared\Casts;

use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class MoneyCasts implements CastsAttributes
{
    /**
     * @param Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return Money
     * @throws UnknownCurrencyException
     */
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

    /**
     * @param Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return array|null
     */
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
