<?php

declare(strict_types=1);

namespace App\Application\PaymentInstruction\DTOs\RecurringDeposit;

use App\Domain\PaymentInstruction\Models\RecurringDeposit;
use App\Domain\Transaction\Enums\TransactionCategoryCode;

final readonly class RecurringDepositReactivationResponseDTO
{
    /**
     * @param RecurringDeposit $recurringDeposit
     */
    public function __construct(
        public RecurringDeposit $recurringDeposit,
    ) {
        // ..
    }

    /**
     * @param RecurringDeposit $recurringDeposit
     * @return self
     */
    public static function fromDomain(
        RecurringDeposit $recurringDeposit,
    ): self {
        return new self(
            recurringDeposit: $recurringDeposit,
        );
    }

    /**
     * @return array[]
     */
    public function toArray(
    ): array {
        return [
            'data' => [
                'type' => 'RecurringDebit',
                'attributes' => [
                    'resource_id' => $this->recurringDeposit->resource_id,
                    'recurring_amount' => $this->recurringDeposit->recurring_amount->getAmount()->__toString(),
                    'initial_amount' => $this->recurringDeposit->initial_amount->getAmount()->__toString(),
                    'start_date' => $this->recurringDeposit->start_date,
                    'end_date' => $this->recurringDeposit->end_date,
                    'frequency' => $this->recurringDeposit->frequency->code,
                    'rollover_enabled' => $this->recurringDeposit->rollover_enabled,
                ],
                'included' => [
                    'service' => [
                        'type' => 'Service',
                        'attributes' => [
                            'service' => config('susubox.susu.name'),
                            'service_code' => TransactionCategoryCode::RECURRING_DEBIT_CODE->value,
                            'service_category' => 'recurring-debit',
                        ],
                    ],
                ],
            ],
        ];
    }
}
