<?php

declare(strict_types=1);

namespace App\Interface\Resources\V1\Account\AccountTransaction;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class AccountTransactionCollectionResource extends JsonResource
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

                'amount' => $this->resource->amount->getAmount()->__toString(),
                'charge' => $this->resource->charge->getAmount()->__toString(),
                'total' => $this->resource->total->getAmount()->__toString(),
                'currency' => $this->resource->total->getCurrency()->__toString(),

                'description' => $this->resource->description,
                'narration' => $this->resource->narration,

                'date_created' => Carbon::parse($this->resource->created_at)->toDateTimeString(),
            ],

            // Relationships
            'relationships' => [
                'account' => [
                    'type' => 'Account',
                    'resource_id' => $this->resource->account->resource_id,
                ],
                'payment_instruction' => [
                    'type' => 'PaymentInstruction',
                    'resource_id' => $this->resource->payment->resource_id,
                ],
                'transaction_category' => [
                    'type' => 'TransactionCategory',
                    'resource_id' => $this->resource->category->resource_id,
                ],
                'wallet' => [
                    'type' => 'Wallet',
                    'resource_id' => $this->resource->wallet->resource_id,
                ],
            ],
        ];
    }
}
