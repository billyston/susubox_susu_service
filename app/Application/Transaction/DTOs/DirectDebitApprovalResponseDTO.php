<?php

declare(strict_types=1);

namespace App\Application\Transaction\DTOs;

use App\Domain\Customer\Models\Wallet;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Transaction\Enums\TransactionCategoryCode;
use Illuminate\Database\Eloquent\Model;

final readonly class DirectDebitApprovalResponseDTO
{
    public function __construct(
        public PaymentInstruction $payment_instruction,
        public Model $product,
        public Wallet $wallet,
    ) {
        // ..
    }

    public static function fromDomain(
        PaymentInstruction $payment_instruction,
        Wallet $wallet,
        Model $product,
        bool $is_initial_debit = false
    ): self {
        return new self(
            payment_instruction: $payment_instruction,
            product: $product,
            wallet: $wallet,
        );
    }

    public function toArray(
    ): array {
        return [
            'data' => [
                'type' => 'DirectDebit',
                'attributes' => [
                    'service' => config('susubox.susu.name'),
                    'service_code' => TransactionCategoryCode::DIRECT_DEBIT_CODE->value,
                    'service_category' => 'direct-debit',
                ],
                'relationships' => [
                    'payment_instruction' => [
                        'type' => 'PaymentInstruction',
                        'attributes' => [
                            'resource_id' => $this->payment_instruction->resource_id,
                            'amount' => $this->payment_instruction->amount->getAmount()->__toString(),
                            'charges' => $this->payment_instruction->charge->getAmount()->__toString(),
                            'total' => $this->payment_instruction->total->getAmount()->__toString(),
                        ],
                    ],
                    'wallet' => [
                        'type' => 'Wallet',
                        'attributes' => [
                            'resource_id' => $this->wallet->resource_id,
                            'wallet_name' => $this->wallet->wallet_name,
                            'wallet_number' => $this->wallet->wallet_number,
                            'wallet_network' => $this->wallet->network_code,
                        ],
                    ],
                ],
            ],
        ];
    }
}
