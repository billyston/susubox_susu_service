<?php

declare(strict_types=1);

namespace App\Application\Transaction\ValueObject;

use App\Domain\Shared\Enums\Statuses;
use App\Domain\Transaction\Enums\TransactionType;
use App\Domain\Transaction\Enums\WithdrawalType;
use Brick\Math\RoundingMode;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;

final readonly class WithdrawalValueObject
{
    /**
     * @param string $withdrawalType
     * @param Money $amount
     * @param Money $charges
     * @param Money $total
     * @param Money $amountPayable
     */
    public function __construct(
        public string $withdrawalType,
        public Money $amount,
        public Money $charges,
        public Money $total,
        public Money $amountPayable,
    ) {
        // ..
    }

    /**
     * @param array $payload
     * @param Money $availableBalance
     * @param float|null $chargePercentage
     * @return self
     * @throws MoneyMismatchException
     * @throws UnknownCurrencyException
     */
    public static function create(
        array $payload,
        Money $availableBalance,
        ?float $chargePercentage = null,
    ): self {
        $data = $payload['data']['attributes'];
        $withdrawalType = $data['withdrawal_type'];

        // Determine withdrawal amount
        $amount = match ($withdrawalType) {
            WithdrawalType::PARTIAL->value => Money::of($data['amount'], $availableBalance->getCurrency()),
            WithdrawalType::FULL->value => $availableBalance,
        };

        // Resolve charges percentage (default 3.2%)
        $chargePercentage ??= 3.2;

        // Compute charges (taken from amount)
        $charges = $amount->multipliedBy($chargePercentage / 100, RoundingMode::HALF_UP);

        // Compute net total
        $total = $amount->minus($charges);

        // Total debit equals amount
        $amountPayable = $total;

        return new self(
            withdrawalType: $withdrawalType,
            amount: $amount,
            charges: $charges,
            total: $total,
            amountPayable: $amountPayable,
        );
    }

    /**
     * @return array
     */
    public function toArray(
    ): array {
        return [
            'amount' => $this->amount,
            'charge' => $this->charges,
            'total' => $this->total,

            'approval_status' => Statuses::PENDING->value,
            'transaction_type' => TransactionType::DEBIT->value,
            'accepted_terms' => true,

            'extra_data' => [
                'amount_payable' => $this->amountPayable,
                'withdrawal_type' => $this->withdrawalType,
            ],
        ];
    }
}
