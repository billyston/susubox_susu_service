<?php

declare(strict_types=1);

namespace App\Interface\Resources\V1\Account\AccountCycle;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class AccountCycleResource extends JsonResource
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
            'type' => 'AccountCycle',

            // Resource exposed attributes
            'attributes' => [
                'resource_id' => $this->resource->resource_id,
                'expected_frequencies' => $this->resource->expected_frequencies,
                'completed_frequencies' => $this->resource->completed_frequencies,
                'expected_amount' => $this->resource->expected_amount->getAmount()->__toString(),
                'contributed_amount' => $this->resource->contributed_amount->getAmount()->__toString(),
                'started_at' => $this->resource->started_at,
                'completed_at' => $this->resource->completed_at,
                'settled_at' => $this->resource->settled_at,
                'status' => $this->resource->status,
            ],

            // Included resource
            'included' => [
                'account_cycle_entries' => $this->resource->entries,
            ],
        ];
    }
}
