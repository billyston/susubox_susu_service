<?php

declare(strict_types=1);

namespace App\Application\Transaction\ValueObject;

use App\Domain\Shared\Enums\Statuses;
use App\Domain\Transaction\Enums\TransactionType;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;

final readonly class RecurringDepositValueObject
{
    /**
     * @param int|null $initialDepositFrequency
     * @param Money $initialDeposit
     * @param Money $susuAmount
     * @param Money $amount
     * @param Money $charge
     * @param Money $total
     * @param string $startDate
     * @param string $endDate
     * @param string $frequency
     * @param bool $rolloverEnabled
     */
    public function __construct(
        public ?int $initialDepositFrequency,
        public Money $initialDeposit,
        public Money $susuAmount,
        public Money $amount,
        public Money $charge,
        public Money $total,
        public string $startDate,
        public string $endDate,
        public string $frequency,
        public bool $rolloverEnabled,
    ) {
        // ..
    }

    /**
     * @param int|null $initialDepositFrequency
     * @param Money $initialDeposit
     * @param Money $susuAmount
     * @param string $startDate
     * @param string $endDate
     * @param string $frequency
     * @param bool $rolloverEnabled
     * @param Money|null $charge
     * @return self
     * @throws MoneyMismatchException
     * @throws UnknownCurrencyException
     */
    public static function create(
        ?int $initialDepositFrequency,
        Money $initialDeposit,
        Money $susuAmount,
        string $startDate,
        string $endDate,
        string $frequency,
        bool $rolloverEnabled,
        ?Money $charge = null,
    ): self {
        // Get the charge (if any)
        $charge ??= Money::of(0, $susuAmount->getCurrency());

        return new self(
            initialDepositFrequency: $initialDepositFrequency,
            initialDeposit: $initialDeposit,
            susuAmount: $susuAmount,
            amount: $susuAmount,
            charge: $charge,
            total: $susuAmount->plus($charge),
            startDate: $startDate,
            endDate: $endDate,
            frequency: $frequency,
            rolloverEnabled: $rolloverEnabled,
        );
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'amount' => $this->amount,
            'charge' => $this->charge,
            'total' => $this->total,
            'approval_status' => Statuses::APPROVED->value,
            'transaction_type' => TransactionType::CREDIT->value,
            'accepted_terms' => true,

            'extra_data' => [
                'is_initial_deposit' => true,
                'initial_deposit_frequency' => $this->initialDepositFrequency,
                'initial_deposit' => $this->initialDeposit,
                'recurring_amount' => $this->susuAmount,
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
                'frequency' => $this->frequency,
                'rollover_enabled' => $this->rolloverEnabled,
            ],
        ];
    }
}
