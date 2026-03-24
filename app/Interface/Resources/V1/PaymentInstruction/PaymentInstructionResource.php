<?php

declare(strict_types=1);

namespace App\Interface\Resources\V1\PaymentInstruction;

use App\Interface\Resources\V1\Account\AccountResource;
use App\Interface\Resources\V1\Transaction\TransactionResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class PaymentInstructionResource extends JsonResource
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
            'type' => 'PaymentInstruction',

            // Resource exposed attributes
            'attributes' => [
                'resource_id' => $this->resource->resource_id,
                'amount' => $this->resource->amount->getAmount()->__toString(),
                'charges' => $this->resource->charge->getAmount()->__toString(),
                'total' => $this->resource->total->getAmount()->__toString(),
                'currency' => $this->resource->total->getCurrency(),
                'approval_status' => $this->resource->approval_status,
                'approved_at' => $this->resource->approved_at,
                'accepted_terms' => $this->resource->accepted_terms,
                'status' => $this->resource->status,
                'date_created' => Carbon::parse($this->resource->created_at)->toDateTimeString(),
            ],

            // Included resource
            'included' => $this->when(
                $this->resource->relationLoaded('account') ||
                $this->resource->relationLoaded('transactions'),
                [
                    'account' => new AccountResource($this->whenLoaded('account')),
                    'transactions' => TransactionResource::collection($this->whenLoaded('transactions')),
                ]
            ),
        ];
    }
}
