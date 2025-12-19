<?php

declare(strict_types=1);

namespace App\Interface\Resources\V1\PaymentInstruction;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class WithdrawalResource extends JsonResource
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
            'type' => 'Withdrawal',

            // Resource exposed attributes
            'attributes' => [
                'resource_id' => $this->resource->resource_id,
                'withdrawal_type' => $this->resource->extra_data['withdrawal_type'],
                'amount' => $this->resource->amount->getAmount()->__toString(),
                'charges' => $this->resource->charge->getAmount()->__toString(),
                'amount_payable' => $this->resource->extra_data['amount_payable']['amount'],
                'total' => $this->resource->total->getAmount()->__toString(),
                'currency' => $this->resource->total->getCurrency(),
                'accepted_terms' => $this->resource->accepted_terms,
                'status' => $this->resource->status,
                'date_created' => Carbon::parse($this->resource->created_at)->toDateTimeString(),
            ],
        ];
    }
}
