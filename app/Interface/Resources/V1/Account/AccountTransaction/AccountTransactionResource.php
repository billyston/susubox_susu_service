<?php

declare(strict_types=1);

namespace App\Interface\Resources\V1\Account\AccountTransaction;

use App\Interface\Resources\V1\Account\AccountResource;
use App\Interface\Resources\V1\Customer\CustomerWalletResource;
use App\Interface\Resources\V1\PaymentInstruction\PaymentInstructionResource;
use App\Interface\Resources\V1\Shared\TransactionCategoryResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class AccountTransactionResource extends JsonResource
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

                'status' => $this->resource->status,
                'status_code' => $this->resource->status_code,

                'amount' => $this->resource->amount->getAmount()->__toString(),
                'charge' => $this->resource->charge->getAmount()->__toString(),
                'total' => $this->resource->total->getAmount()->__toString(),
                'currency' => $this->resource->total->getCurrency()->__toString(),

                'description' => $this->resource->description,
                'narration' => $this->resource->narration,

                'date_created' => Carbon::parse($this->resource->created_at)->toDateTimeString(),
            ],

            // Included resource
            'included' => [
                'payment_instruction' => new PaymentInstructionResource($this->resource->payment),
                'account' => new AccountResource($this->resource->account),
                'transaction_category' => new TransactionCategoryResource($this->resource->category),
                'wallet' => new CustomerWalletResource($this->resource->wallet),
            ],
        ];
    }
}
