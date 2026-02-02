<?php

declare(strict_types=1);

namespace App\Application\Transaction\DTOs\RecurringDeposit;

use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Transaction\Enums\TransactionCategoryCode;

final readonly class RecurringDepositApprovalResponseDTO
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
            'initial_deposit' => $this->paymentInstruction->getMetadata()['initial_deposit']['amount'],
            'charges' => $this->paymentInstruction->charge->getAmount()->__toString(),
            'amount' => $this->paymentInstruction->total->getAmount()->__toString(),
        ];

        // Only include internal_reference if it exists
        if (! empty($this->paymentInstruction->internal_reference)) {
            $paymentInstructionAttributes['internal_reference'] =
                $this->paymentInstruction->internal_reference;
        }

        return [
            'data' => [
                'type' => 'RecurringDebit',
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
                    'product' => [
                        'type' => 'Product',
                        'attributes' => [
                            'start_date' => $this->paymentInstruction->getMetadata()['start_date'],
                            'end_date' => $this->paymentInstruction->getMetadata()['end_date'],
                            'frequency' => $this->paymentInstruction->getMetadata()['frequency'],
                            'rollover_enabled' => $this->paymentInstruction->getMetadata()['rollover_enabled'],
                        ],
                    ],
                    'wallet' => [
                        'type' => 'Wallet',
                        'attributes' => [
                            'wallet_name' => $this->paymentInstruction->wallet->wallet_name,
                            'wallet_number' => $this->paymentInstruction->wallet->wallet_number,
                            'wallet_network' => $this->paymentInstruction->wallet->network_code,
                        ],
                    ],
                ],
            ],
        ];
    }
}
