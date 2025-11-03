<?php

declare(strict_types=1);

namespace App\Interface\Resources\V1\Account;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class DirectDepositResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {
        // Return the resource array
        return [
            // Resource type and id
            'type' => 'DirectDeposit',
            'resource_id' => $this->resource->resource_id,

            // Resource exposed attributes
            'attributes' => [
                'deposited_in' => $this->resource->deposited_in,
                'amount' => $this->resource->amount->getAmount(),
                'charges' => $this->resource->charge->getAmount(),
                'total' => $this->resource->total->getAmount(),
                'accepted_terms' => $this->resource->accepted_terms,
                'status' => $this->resource->status,
                'date_created' => Carbon::parse($this->resource->created_at)->toDateTimeString(),
            ],
        ];
    }
}
