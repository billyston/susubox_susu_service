<?php

declare(strict_types=1);

namespace App\Interface\Resources\V1\Account\AccountCycle;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class AccountCycleEntryResource extends JsonResource
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
            'type' => 'AccountCycleEntries',

            // Resource exposed attributes
            'attributes' => [
                'resource_id' => $this->resource->resource_id,
                'frequencies' => $this->resource->frequencies,
                'amount' => $this->resource->amount->getAmount()->__toString(),
                'entry_type' => $this->resource->entry_type,
                'posted_at' => $this->resource->posted_at,
                'status' => $this->resource->status,
            ],
        ];
    }
}
