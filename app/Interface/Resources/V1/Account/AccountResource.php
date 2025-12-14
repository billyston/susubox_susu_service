<?php

declare(strict_types=1);

namespace App\Interface\Resources\V1\Account;

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
            'resource_id' => $this->resource->resource_id,

            // Resource exposed attributes
            'attributes' => [
                'account_name' => $this->resource->account_name,
                'account_number' => $this->resource->account_number,
                'account_activity_period' => Carbon::parse($this->resource->account_activity_period)->toDateTimeString(),
                'status' => $this->resource->status,
                'date_created' => Carbon::parse($this->resource->created_at)->toDateTimeString(),
            ],
        ];
    }
}
