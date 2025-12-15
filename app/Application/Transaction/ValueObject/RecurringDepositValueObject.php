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
    public function __construct(
        public Money $initial_deposit,
        public Money $susu_amount,
        public Money $amount,
        public Money $charge,
        public Money $total,
        public string $start_date,
        public string $end_date,
        public string $frequency,
        public bool $rollover_enabled,
    ) {
        // ..
    }

    /**
     * @param Money $initial_deposit
     * @param Money $susu_amount
     * @param ?Money $charge
     * @param string $start_date
     * @param string $end_date
     * @param string $frequency
     * @param bool $rollover_enabled
     * @return RecurringDepositValueObject
     * @throws MoneyMismatchException
     * @throws UnknownCurrencyException
     */
    public static function create(
        Money $initial_deposit,
        Money $susu_amount,
        string $start_date,
        string $end_date,
        string $frequency,
        bool $rollover_enabled,
    ): self {
        // Get the charge
        $charge = $charge ?? Money::of(0.00, 'GHS');

        return new self(
            initial_deposit: $initial_deposit,
            susu_amount: $susu_amount,
            amount: $initial_deposit,
            charge: $charge,
            total: $initial_deposit->plus($charge),
            start_date: $start_date,
            end_date: $end_date,
            frequency: $frequency,
            rollover_enabled: $rollover_enabled,
        );
    }

    public function toArray(
    ): array {
        return [
            'amount' => $this->initial_deposit,
            'charge' => $this->charge,
            'total' => $this->total,
            'approval_status' => Statuses::APPROVED->value,
            'transaction_type' => TransactionType::CREDIT->value,
            'accepted_terms' => true,

            'extra_data' => [
                'is_initial_deposit' => true,
                'initial_deposit' => $this->initial_deposit,
                'recurring_amount' => $this->susu_amount,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'frequency' => $this->frequency,
                'rollover_enabled' => $this->rollover_enabled,
            ],
        ];
    }
}
