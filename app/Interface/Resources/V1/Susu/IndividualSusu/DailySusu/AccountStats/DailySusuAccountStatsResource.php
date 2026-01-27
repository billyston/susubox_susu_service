<?php

declare(strict_types=1);

namespace App\Interface\Resources\V1\Susu\IndividualSusu\DailySusu\AccountStats;

use App\Interface\Resources\V1\Account\AccountCycle\AccountCycleDefinitionResource;
use App\Interface\Resources\V1\Account\AccountCycle\AccountCycleResource;
use App\Interface\Resources\V1\Account\AccountStats\AccountTransactionGapResource;
use App\Interface\Resources\V1\Account\AccountStats\AccountTransactionPerformanceStatsResource;
use App\Interface\Resources\V1\Account\AccountStats\AccountTransactionStatsResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class DailySusuAccountStatsResource extends JsonResource
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
            'type' => 'DailySusu',

            // Resource exposed attributes
            'attributes' => new AccountCycleDefinitionResource(resource: $this->resource->cycleDefinition),

            // Included resource
            'included' => [
                'cycles' => AccountCycleResource::collection(resource: $this->resource->cycles),
                'transaction_performance' => new AccountTransactionPerformanceStatsResource(resource: $this->resource->account->transactionPerformance),
                'transaction_stats' => new AccountTransactionStatsResource(resource: $this->resource->account->transactionStats),
                'transaction_gap_stats' => new AccountTransactionGapResource(resource: $this->resource->account->transactionGapStats),
            ],
        ];
    }
}
