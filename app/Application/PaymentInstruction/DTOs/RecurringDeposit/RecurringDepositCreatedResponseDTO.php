<?php

declare(strict_types=1);

namespace App\Application\PaymentInstruction\DTOs\RecurringDeposit;

use App\Domain\Customer\Models\Wallet;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\PaymentInstruction\Models\RecurringDeposit;
use App\Domain\Transaction\Enums\TransactionCategoryCode;
use Carbon\Carbon;

final readonly class RecurringDepositCreatedResponseDTO
{
    /**
     * @param RecurringDeposit $recurringDeposit
     * @param PaymentInstruction $paymentInstruction
     * @param Wallet $wallet
     */
    public function __construct(
        public RecurringDeposit $recurringDeposit,
        public PaymentInstruction $paymentInstruction,
        private Wallet $wallet,
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
        // Extract the key resources
        $paymentInstruction = $recurringDeposit->paymentInstruction;
        $wallet = $paymentInstruction->wallet;

        return new self(
            recurringDeposit: $recurringDeposit,
            paymentInstruction: $paymentInstruction,
            wallet: $wallet
        );
    }

    /**
     * @return array[]
     */
    public function toArray(
    ): array {
        return [
            'data' => [
                'type' => 'RecurringDeposit',
                'attributes' => [
                    'resource_id' => $this->recurringDeposit->resource_id,
                    'recurring_amount' => $this->recurringDeposit->recurring_amount->getAmount()->__toString(),
                    'initial_amount' => $this->recurringDeposit->initial_amount->getAmount()->__toString(),
                    'start_date' => Carbon::parse($this->recurringDeposit->start_date)->toDateTimeString(),
                    'end_date' => Carbon::parse($this->recurringDeposit->end_date)->toDateTimeString(),
                    'frequency' => $this->recurringDeposit->frequency->code,
                    'rollover_enabled' => $this->recurringDeposit->rollover_enabled,
                ],
                'included' => [
                    'payment_instruction' => [
                        'type' => 'PaymentInstruction',
                        'attributes' => [
                            'resource_id' => $this->paymentInstruction->resource_id,
                            'amount' => $this->paymentInstruction->amount->getAmount()->__toString(),
                            'charge' => $this->paymentInstruction->charge->getAmount()->__toString(),
                        ],
                    ],
                    'wallet' => [
                        'type' => 'Wallet',
                        'attributes' => [
                            'wallet_name' => $this->wallet->wallet_name,
                            'wallet_number' => $this->wallet->wallet_number,
                            'wallet_network' => $this->wallet->network_code,
                        ],
                    ],
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
