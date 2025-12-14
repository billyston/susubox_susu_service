<?php

declare(strict_types=1);

namespace App\Application\Transaction\DTOs;

use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Transaction\Enums\TransactionCategoryCode;

final readonly class RecurringDebitApprovalResponseDTO
{
    public function __construct(
        public PaymentInstruction $payment_instruction,
    ) {
        // ..
    }

    public static function fromDomain(
        PaymentInstruction $payment_instruction,
    ): self {
        return new self(
            payment_instruction: $payment_instruction,
        );
    }

    public function toArray(
    ): array {
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
                        'attributes' => [
                            'resource_id' => $this->payment_instruction->resource_id,
                            'recurring_amount' => $this->payment_instruction->getMetadata()['recurring_amount']['amount'],
                            'amount' => $this->payment_instruction->amount->getAmount()->__toString(),
                            'charges' => $this->payment_instruction->charge->getAmount()->__toString(),
                            'total' => $this->payment_instruction->total->getAmount()->__toString(),
                        ],
                    ],
                    'product' => [
                        'type' => 'Product',
                        'attributes' => [
                            'start_date' => $this->payment_instruction->getMetadata()['start_date'],
                            'end_date' => $this->payment_instruction->getMetadata()['end_date'],
                            'frequency' => $this->payment_instruction->getMetadata()['frequency'],
                            'rollover_enabled' => $this->payment_instruction->getMetadata()['rollover_enabled'],
                        ],
                    ],
                    'wallet' => [
                        'type' => 'Wallet',
                        'attributes' => [
                            'wallet_name' => $this->payment_instruction->wallet->wallet_name,
                            'wallet_number' => $this->payment_instruction->wallet->wallet_number,
                            'wallet_network' => $this->payment_instruction->wallet->network_code,
                        ],
                    ],
                ],
            ],
        ];
    }
}
