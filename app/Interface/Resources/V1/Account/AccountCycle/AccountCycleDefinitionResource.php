<?php

declare(strict_types=1);

namespace App\Interface\Resources\V1\Account\AccountCycle;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class AccountCycleDefinitionResource extends JsonResource
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
            'type' => 'AccountCycleDefinition',

            // Resource exposed attributes
            'attributes' => [
                'resource_id' => $this->resource->resource_id,
                'cycle_length' => $this->resource->cycle_length,
                'commission_frequencies' => $this->resource->commission_frequencies,
                'settlement_frequencies' => $this->resource->settlement_frequencies,
                'expected_frequencies' => $this->resource->expected_frequencies,
                'expected_cycle_amount' => $this->resource->expected_cycle_amount->getAmount()->__toString(),
                'expected_settlement_amount' => $this->resource->expected_settlement_amount->getAmount()->__toString(),
                'commission_amount' => $this->resource->commission_amount->getAmount()->__toString(),
            ],
        ];
    }
}
