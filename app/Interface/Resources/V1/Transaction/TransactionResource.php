<?php

declare(strict_types=1);

namespace App\Interface\Resources\V1\Transaction;

use App\Interface\Resources\V1\Customer\WalletResource;
use App\Interface\Resources\V1\PaymentInstruction\PaymentInstructionResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class TransactionResource extends JsonResource
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
            'type' => 'Transaction',

            // Resource exposed attributes
            'attributes' => [
                'resource_id' => $this->resource->resource_id,
                'transaction_type' => $this->resource->transaction_type,
                'reference_number' => $this->resource->reference_number,
                'status_code' => $this->resource->status_code,
                'status' => $this->resource->status,
                'amount' => $this->resource->amount->getAmount()->toString(),
                'charge' => $this->resource->charge->getAmount()->toString(),
                'total' => $this->resource->total->getAmount()->toString(),
                'currency' => $this->resource->total->getCurrency(),
                'description' => $this->resource->description,
                'narration' => $this->resource->narration,
                'date_created' => Carbon::parse($this->resource->created_at)->toDateTimeString(),
            ],

            // Included resource
            'included' => $this->when(
                $this->resource->relationLoaded('account') ||
                $this->resource->relationLoaded('paymentInstruction') ||
                $this->resource->relationLoaded('linkedWallet'),
                [
                    'payment_instruction' => new PaymentInstructionResource($this->whenLoaded('paymentInstruction')),
                    'transaction_categories' => new TransactionCategoryResource($this->whenLoaded('transactionCategory')),
                    'linked_wallet' => new WalletResource($this->whenLoaded('linkedWallet')),
                ]
            ),
        ];
    }
}
