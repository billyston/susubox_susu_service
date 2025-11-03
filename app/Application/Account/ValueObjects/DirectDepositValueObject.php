<?php

namespace App\Application\Account\ValueObjects;

use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Money;
use LogicException;

final readonly class DirectDepositValueObject
{
    public function __construct(
        private string $deposit_type,
        private Money $susu_amount,
        private ?int $frequencies = null,
        private ?Money $amount = null,
    ) {
        // ..
    }

    public function depositAmount(
    ): Money {
        return match ($this->deposit_type) {
            'frequency' => $this->susu_amount->multipliedBy($this->frequencies),
            'amount' => $this->amount,
            default => throw new LogicException('Invalid deposit type.'),
        };
    }

    public function charges(
    ): Money {
        return $this->depositAmount()->multipliedBy('0.00');
    }

    /**
     * @throws MoneyMismatchException
     */
    public function total(
    ): Money {
        return $this->depositAmount()->plus($this->charges());
    }
}
