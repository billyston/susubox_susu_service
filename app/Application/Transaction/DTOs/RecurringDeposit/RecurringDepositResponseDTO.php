<?php

declare(strict_types=1);

namespace App\Application\Transaction\DTOs\RecurringDeposit;

use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Transaction\Enums\TransactionCategoryCode;

final readonly class RecurringDepositResponseDTO
{
    /**
     * @param PaymentInstruction $paymentInstruction
     * @param string $action
     */
    public function __construct(
        public PaymentInstruction $paymentInstruction,
        public string $action,
    ) {
        // ..
    }

    /**
     * @param PaymentInstruction $paymentInstruction
     * @param string $action
     * @return self
     */
    public static function fromDomain(
        PaymentInstruction $paymentInstruction,
        string $action
    ): self {
        return new self(
            paymentInstruction: $paymentInstruction,
            action: $action,
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
            'action' => $this->action,
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
