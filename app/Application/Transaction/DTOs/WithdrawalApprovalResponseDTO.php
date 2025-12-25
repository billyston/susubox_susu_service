<?php

declare(strict_types=1);

namespace App\Application\Transaction\DTOs;

use App\Domain\Customer\Models\Wallet;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Transaction\Enums\TransactionCategoryCode;
use Illuminate\Database\Eloquent\Model;

final readonly class WithdrawalApprovalResponseDTO
{
    /**
     * @param PaymentInstruction $paymentInstruction
     * @param Model $product
     * @param Wallet $wallet
     */
    public function __construct(
        public PaymentInstruction $paymentInstruction,
        public Model $product,
        public Wallet $wallet,
    ) {
        // ..
    }

    /**
     * @param PaymentInstruction $paymentInstruction
     * @param Wallet $wallet
     * @param Model $product
     * @return self
     */
    public static function fromDomain(
        PaymentInstruction $paymentInstruction,
        Wallet $wallet,
        Model $product,
    ): self {
        return new self(
            paymentInstruction: $paymentInstruction,
            product: $product,
            wallet: $wallet,
        );
    }

    /**
     * @return array[]
     */
    public function toArray(
    ): array {
        return [
            'data' => [
                'type' => 'Withdrawal',
                'attributes' => [
                    'service' => config('susubox.susu.name'),
                    'service_code' => TransactionCategoryCode::WITHDRAWAL_CODE->value,
                    'service_category' => 'withdrawal',
                ],
                'relationships' => [
                    'payment_instruction' => [
                        'type' => 'PaymentInstruction',
                        'attributes' => [
                            'resource_id' => $this->paymentInstruction->resource_id,
                            'charges' => $this->paymentInstruction->charge->getAmount()->__toString(),
                            'amount' => $this->paymentInstruction->total->getAmount()->__toString(),
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
