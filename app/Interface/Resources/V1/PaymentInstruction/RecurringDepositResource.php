<?php

declare(strict_types=1);

namespace App\Interface\Resources\V1\PaymentInstruction;

use App\Interface\Resources\V1\Transaction\TransactionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class RecurringDepositResource extends JsonResource
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
            'type' => 'RecurringDeposit',

            // Resource exposed attributes
            'attributes' => [
                'resource_id' => $this->resource->resource_id,
                'frequencies' => $this->resource->frequency->code,
                'recurring_amount' => $this->resource->recurring_amount->getAmount()->__toString(),
                'initial_amount' => $this->resource->initial_amount->getAmount()->__toString(),
                'initial_frequency' => $this->resource->initial_frequency,
                'start_date' => $this->resource->start_date,
                'rollover_enabled' => $this->resource->rollover_enabled,
                'status' => $this->resource->status,
            ],

            // Included resource
            'included' => $this->when(
                $this->resource->relationLoaded('paymentInstruction') ||
                $this->resource->relationLoaded('transactions'),
                [
                    'payment_instruction' => new PaymentInstructionResource($this->whenLoaded('paymentInstruction')),
                    'transaction' => TransactionResource::collection($this->whenLoaded('transactions')),
                ]
            ),
        ];
    }
}
