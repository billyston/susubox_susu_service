<?php

declare(strict_types=1);

namespace Domain\Susu\Data\Account;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class AccountResource extends JsonResource
{
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
                'purpose' => $this->resource->purpose,
                'susu_amount' => number_format(num: $this->resource->amount->getAmount()->toFloat(), decimals: 2),
                'currency' => $this->resource->amount->getCurrency(),
                'account_activity_period' => Carbon::parse($this->resource->account_activity_period)->toDateTimeString(),
                'status' => $this->resource->status,
                'date_created' => Carbon::parse($this->resource->created_at)->toDateTimeString(),
            ],
        ];
    }
}
