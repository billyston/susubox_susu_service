<?php

declare(strict_types=1);

namespace App\Application\Transaction\Services\Statistics;

use Brick\Math\RoundingMode;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Money;
use Illuminate\Support\Collection;

final class MoneyCalculator
{
    /**
     * @param Money $money
     * @return string
     */
    public function formatMoney(
        Money $money
    ): string {
        return number_format(
            $money->getAmount()->toFloat(),
            decimals: 2,
            thousands_separator: ''
        );
    }

    /**
     * @param Collection $collection
     * @param string $field
     * @return Money
     * @throws MoneyMismatchException
     */
    public function sumMoney(
        Collection $collection,
        string $field
    ): Money {
        return $collection->reduce(
            function (?Money $carry, $item) use ($field) {
                $money = $item->$field;
                return $carry ? $carry->plus($money) : $money;
            },
            Money::zero('GHS')
        );
    }

    /**
     * @param Collection $collection
     * @param string $field
     * @return Money|null
     * @throws MoneyMismatchException
     */
    public function averageMoney(
        Collection $collection,
        string $field
    ): ?Money {
        if ($collection->isEmpty()) {
            return null;
        }

        $sum = $this->sumMoney(
            collection: $collection,
            field: $field
        );
        $count = $collection->count();

        return $sum->dividedBy(
            that: $count,
            roundingMode: RoundingMode::HALF_UP
        );
    }

    /**
     * @param Collection $collection
     * @param string $field
     * @return float
     */
    public function calculateMoneyMedian(
        Collection $collection,
        string $field
    ): float {
        if ($collection->isEmpty()) {
            return 0;
        }

        $sorted = $collection->sortBy(function ($item) use ($field) {
            return $item->$field->getAmount()->toFloat();
        })->values();

        $count = $sorted->count();

        if ($count % 2 === 1) {
            $medianMoney = $sorted->get(floor($count / 2))->$field;
            return $medianMoney->getAmount()->toFloat();
        }

        $medianMoney1 = $sorted->get($count / 2 - 1)->$field;
        $medianMoney2 = $sorted->get($count / 2)->$field;

        return $medianMoney1->plus($medianMoney2)
            ->dividedBy(2, RoundingMode::HALF_UP)
            ->getAmount()->toFloat();
    }
}
