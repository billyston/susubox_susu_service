<?php

declare(strict_types=1);

namespace App\Interface\Resources\V1\Susu\IndividualSusu\DailySusu;

use App\Interface\Resources\V1\Account\AccountCycle\AccountCycleDefinitionResource;
use App\Interface\Resources\V1\Account\AccountResource;
use App\Interface\Resources\V1\Customer\CustomerResource;
use App\Interface\Resources\V1\Customer\WalletResource;
use App\Interface\Resources\V1\PaymentInstruction\PaymentInstructionResource;
use App\Interface\Resources\V1\PaymentInstruction\RecurringDepositResource;
use App\Interface\Resources\V1\Transaction\TransactionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class DailySusuResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray(
        Request $request
    ): array {
        // Return the resource array
        return [
            // Resource type and id
            'type' => 'DailySusu',

            // Resource exposed attributes
            'attributes' => [
                'resource_id' => $this->resource->resource_id,
                'is_collateralized' => $this->resource->is_collateralized,
                'payout_status' => $this->resource->payout_status,
                'auto_payout' => $this->resource->auto_payout,
            ],

            // Included resource
            'included' => $this->when(
                $this->resource->relationLoaded('account') ||
                $this->resource->relationLoaded('accountCycleDefinition') ||
                $this->resource->relationLoaded('recurringDeposit') ||
                $this->resource->relationLoaded('paymentInstructions') ||
                $this->resource->relationLoaded('transactions') ||
                $this->resource->relationLoaded('customer') ||
                $this->resource->relationLoaded('linkedWallet'),
                [
                    'account' => new AccountResource($this->whenLoaded('account')),
                    'account_cycle_definition' => new AccountCycleDefinitionResource($this->whenLoaded('accountCycleDefinition')),
                    'recurring_deposit' => new RecurringDepositResource($this->whenLoaded('recurringDeposit')),
                    'payment_instructions' => PaymentInstructionResource::collection($this->whenLoaded('paymentInstructions')),
                    'transactions' => TransactionResource::collection($this->whenLoaded('transactions')),
                    'customer' => new CustomerResource($this->whenLoaded('customer')),
                    'linked_wallet' => new WalletResource($this->whenLoaded('linkedWallet')),
                ]
            ),
        ];
    }
}
