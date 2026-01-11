<?php

declare(strict_types=1);

namespace App\Interface\Resources\V1\Susu\IndividualSusu\DailySusu;

use App\Interface\Resources\V1\Account\AccountCycle\AccountCycleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class DailySusuSettlementResource extends JsonResource
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
            'type' => 'DailySusuSettlement',

            // Resource exposed attributes
            'attributes' => [
                'resource_id' => $this->resource->resource_id,
                'settlement_scope' => $this->resource->settlement_scope,
                'principal_amount' => $this->resource->principal_amount->getAmount()->__toString(),
                'charge_amount' => $this->resource->charge_amount->getAmount()->__toString(),
                'total_amount' => $this->resource->total_amount->getAmount()->__toString(),
                'completed_at' => $this->resource->completed_at,
                'status' => $this->resource->status,
            ],

            // Included resource
            'included' => [
                'account_cycles' => AccountCycleResource::collection($this->resource->cycles),
            ],
        ];
    }
}
