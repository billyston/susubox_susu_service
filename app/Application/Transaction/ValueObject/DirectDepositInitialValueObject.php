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
    /**
     * @param Money $initialDeposit
     * @param Money $charge
     * @param Money $amount
     * @param Money $total
     */
    public function __construct(
        public Money $initialDeposit,
        public Money $charge,
        public Money $amount,
        public Money $total,
    ) {
        // ..
    }

    /**
     * @param Money $initialDeposit
     * @param Money|null $amount
     * @param Money|null $charge
     * @return self
     * @throws MoneyMismatchException
     * @throws UnknownCurrencyException
     */
    public static function create(
        Money $initialDeposit,
        Money $amount = null,
        Money $charge = null,
    ): self {
        // Determine the transaction amount
        if ($amount === null) {
            $amount = $initialDeposit;
        }

        // Get the charge
        $charge = $charge ?? Money::of(0.00, 'GHS');

        return new self(
            initialDeposit: $initialDeposit,
            charge: $charge,
            amount: $amount,
            total: $amount->plus($charge),
        );
    }

    /**
     * @return array
     */
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
                'initial_deposit' => $this->initialDeposit,
            ],
        ];
    }
}
