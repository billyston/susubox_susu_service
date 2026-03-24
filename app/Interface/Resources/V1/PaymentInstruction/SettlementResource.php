<?php

declare(strict_types=1);

namespace App\Interface\Resources\V1\PaymentInstruction;

use App\Interface\Resources\V1\Account\AccountCycle\AccountCycleResource;
use App\Interface\Resources\V1\Transaction\TransactionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class SettlementResource extends JsonResource
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
            'type' => 'Settlement',

            // Resource exposed attributes
            'attributes' => [
                'resource_id' => $this->resource->resource_id,
                'settlement_scope' => $this->resource->settlement_scope,
                'principal_amount' => $this->resource->principal_amount->getAmount()->__toString(),
                'charge_amount' => $this->resource->charge_amount->getAmount()->__toString(),
                'total_amount' => $this->resource->total_amount->getAmount()->__toString(),
                'completed_at' => $this->resource->completed_at,
                'status' => $this->resource->status,
            ],

            // Included resource
            'included' => $this->when(
                $this->resource->relationLoaded('paymentInstruction') ||
                $this->resource->relationLoaded('transaction') ||
                $this->resource->relationLoaded('accountCycles'),
                [
                    'payment_instruction' => new PaymentInstructionResource($this->whenLoaded('paymentInstruction')),
                    'transaction' => new TransactionResource($this->whenLoaded('transaction')),
                    'account_cycles' => AccountCycleResource::collection($this->whenLoaded('accountCycles')),
                ]
            ),
        ];
    }
}
