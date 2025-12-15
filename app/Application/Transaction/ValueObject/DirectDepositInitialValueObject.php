<?php

declare(strict_types=1);

namespace App\Application\Transaction\ValueObject;

use App\Domain\Shared\Enums\Statuses;
use App\Domain\Transaction\Enums\TransactionType;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;

final readonly class DirectDepositInitialValueObject
{
    public function __construct(
        public Money $initial_deposit,
        public Money $charge,
        public Money $amount,
        public Money $total,
    ) {
        // ..
    }

    /**
     * @param TransactionType $transaction_type
     * @param Money|null $initial_deposit
     * @param Money|null $amount
     * @param Money|null $charge
     * @param bool $isPercentage
     * @return DirectDepositInitialValueObject
     * @throws MoneyMismatchException
     * @throws UnknownCurrencyException
     */
    public static function create(
        Money $initial_deposit,
        Money $amount = null,
        Money $charge = null,
    ): self {
        // Determine the transaction amount
        if ($amount === null) {
            $amount = $initial_deposit;
        }

        // Get the charge
        $charge = $charge ?? Money::of(0.00, 'GHS');

        return new self(
            initial_deposit: $initial_deposit,
            charge: $charge,
            amount: $amount,
            total: $amount->plus($charge),
        );
    }

    public function toArray(
    ): array {
        return [
            'charge' => $this->charge,
            'amount' => $this->amount,
            'total' => $this->total,
            'approval_status' => Statuses::APPROVED->value,
            'transaction_type' => TransactionType::CREDIT->value,
            'accepted_terms' => true,

            'extra_data' => [
                'is_initial_deposit' => true,
                'initial_deposit' => $this->initial_deposit,
            ],
        ];
    }
}
