<?php

declare(strict_types=1);

namespace App\Application\Transaction\DTOs\RecurringDeposit;

use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Transaction\Enums\TransactionCategoryCode;

final readonly class RecurringDepositResponseDTO
{
    /**
     * @param PaymentInstruction $paymentInstruction
     */
    public function __construct(
        public PaymentInstruction $paymentInstruction,
    ) {
        // ..
    }

    /**
     * @param PaymentInstruction $paymentInstruction
     * @return self
     */
    public static function fromDomain(
        PaymentInstruction $paymentInstruction,
    ): self {
        return new self(
            paymentInstruction: $paymentInstruction,
        );
    }

    /**
     * @return array[]
     */
    public function toArray(
    ): array {
        // Build payment instruction attributes first
        $paymentInstructionAttributes = [
            'resource_id' => $this->paymentInstruction->resource_id,
        ];

        // Only include internal_reference if it exists
        if (! empty($this->paymentInstruction->internal_reference)) {
            $paymentInstructionAttributes['internal_reference'] =
                $this->paymentInstruction->internal_reference;
        }

        return [
            'data' => [
                'type' => 'Pause',
                'attributes' => [
                    'service' => config('susubox.susu.name'),
                    'service_code' => TransactionCategoryCode::RECURRING_DEBIT_CODE->value,
                    'service_category' => 'recurring-debit',
                ],
                'relationships' => [
                    'payment_instruction' => [
                        'type' => 'PaymentInstruction',
                        'attributes' => $paymentInstructionAttributes,
                    ],
                ],
            ],
        ];
    }
}
