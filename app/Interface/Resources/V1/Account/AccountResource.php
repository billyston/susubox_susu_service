<?php

declare(strict_types=1);

namespace App\Interface\Resources\V1\Account;

use App\Interface\Resources\V1\PaymentInstruction\PaymentInstructionResource;
use App\Interface\Resources\V1\Shared\SusuSchemeResource;
use App\Interface\Resources\V1\Transaction\TransactionResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class AccountResource extends JsonResource
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
            'type' => 'Account',

            // Resource exposed attributes
            'attributes' => [
                'resource_id' => $this->resource->resource_id,
                'account_name' => $this->resource->account_name,
                'account_number' => $this->resource->account_number,
                'account_type' => $this->resource->account_type,
                'status' => $this->resource->status,
                'date_created' => Carbon::parse($this->resource->created_at)->toDateTimeString(),
            ],

            // Included resource
            'included' => $this->when(
                $this->resource->relationLoaded('susuScheme') ||
                $this->resource->relationLoaded('paymentInstructions') ||
                $this->resource->relationLoaded('transactions'),
                [
                    'susu_scheme' => new SusuSchemeResource($this->whenLoaded('susuScheme')),
                    'payment_instructions' => PaymentInstructionResource::collection($this->whenLoaded('paymentInstructions')),
                    'transactions' => TransactionResource::collection($this->whenLoaded('transactions')),
                ]
            ),
        ];
    }
}
