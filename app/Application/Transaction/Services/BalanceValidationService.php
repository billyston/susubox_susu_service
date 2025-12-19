<?php

declare(strict_types=1);

namespace App\Application\Transaction\Services;

use App\Domain\Transaction\Exceptions\InsufficientBalanceException;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Money;

final class BalanceValidationService
{
    /**
     * @param Money $availableBalance
     * @param Money $debitAmount
     * @return void
     * @throws InsufficientBalanceException
     * @throws MoneyMismatchException
     */
    public function execute(
        Money $availableBalance,
        Money $debitAmount
    ): void {
        if (! $availableBalance->isGreaterThanOrEqualTo($debitAmount)) {
            throw new InsufficientBalanceException();
        }
    }
}
