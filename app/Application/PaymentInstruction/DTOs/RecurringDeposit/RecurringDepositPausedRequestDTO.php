<?php

declare(strict_types=1);

namespace App\Application\PaymentInstruction\DTOs\RecurringDeposit;

use App\Domain\Shared\Enums\Currency;
use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;
use Illuminate\Support\Carbon;

final readonly class RecurringDepositPausedRequestDTO
{
    /**
     * @param string $resourceID
     * @param Money $recurringAmount
     * @param Money $initialDeposit
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @param string $frequency
     * @param bool $rolloverEnabled
     * @param string $status
     */
    public function __construct(
        public string $resourceID,
        public Money $recurringAmount,
        public Money $initialDeposit,
        public Carbon $startDate,
        public Carbon $endDate,
        public string $frequency,
        public bool $rolloverEnabled,
        public string $status,
    ) {
        //..
    }

    /**
     * @param array $payload
     * @return self
     * @throws UnknownCurrencyException
     */
    public static function fromPayload(
        array $payload
    ): self {
        // Extract the key arrays
        $data = $payload['data'];

        // Extract the main resources
        $recurringDeposit = $data['attributes'];

        return new self(
            resourceID: $recurringDeposit['resource_id'],
            recurringAmount: Money::of($recurringDeposit['recurring_amount'], Currency::GHANA_CEDI->value),
            initialDeposit: Money::of($recurringDeposit['initial_debit'], Currency::GHANA_CEDI->value),
            startDate: Carbon::parse($recurringDeposit['start_date']),
            endDate: Carbon::parse($recurringDeposit['end_date']),
            frequency: $recurringDeposit['frequency'],
            rolloverEnabled: $recurringDeposit['rollover_enabled'],
            status: $recurringDeposit['status'],
        );
    }

    /**
     * @return array
     */
    public function toArray(
    ): array {
        return [
        ];
    }
}
