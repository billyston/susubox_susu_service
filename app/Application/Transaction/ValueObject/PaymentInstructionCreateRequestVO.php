<?php

declare(strict_types=1);

namespace App\Application\Transaction\ValueObject;

use App\Domain\Transaction\Enums\TransactionType;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;

final readonly class PaymentInstructionCreateRequestVO
{
    public function __construct(
        public TransactionType $transaction_type,
        public ?Money $initial_amount,
        public ?Money $charge,
        public ?Money $amount,
        public Money $total,
    ) {
        // ..
    }

    /**
     * @throws UnknownCurrencyException
     * @throws MoneyMismatchException
     */
    public static function create(
        TransactionType $transaction_type,
        ?Money $initial_amount,
        Money|null $amount,
        Money|null $charge,
        bool $isPercentage = false,
    ): self {
        // If both initial_amount and amount are null, assign zero Money
        if ($amount === null) {
            $amount = Money::of(0, 'GHS');
        }

        // Base for charge calculation: initial_amount if exists, else amount
        $baseForCharge = $initial_amount ?? $amount;

        $normalized_charge = self::normalizeCharge(
            amount: $baseForCharge,
            charge: $charge,
            isPercentage: $isPercentage
        );

        // Compute total using baseForCharge ± charge
        $total = match ($transaction_type) {
            TransactionType::DEBIT => $baseForCharge->plus($normalized_charge),
            TransactionType::CREDIT => $baseForCharge->minus($normalized_charge),
        };

        return new self(
            transaction_type: $transaction_type,
            initial_amount: $initial_amount,
            charge: $normalized_charge,
            amount: $amount,
            total: $total,
        );
    }

    public function toArray(
    ): array {
        return [
            'transaction_type' => $this->transaction_type->value,
            'initial_amount' => $this->initial_amount,
            'charge' => $this->charge,
            'amount' => $this->amount,
            'total' => $this->total,
        ];
    }

    private static function normalizeCharge(
        Money $amount,
        Money|float|null $charge,
        bool $isPercentage
    ): Money {
        return match (true) {
            $charge === null => Money::of(0, $amount->getCurrency()),
            $charge instanceof Money => $charge,
            is_float($charge) && $isPercentage => $amount->multipliedBy($charge),
            is_float($charge) && ! $isPercentage => Money::of($charge, $amount->getCurrency()),
            default => Money::of(0, $amount->getCurrency()),
        };
    }
}
