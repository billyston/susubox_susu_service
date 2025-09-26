<?php

declare(strict_types=1);

namespace Domain\Susu\Data\DailySusu;

use Carbon\Carbon;
use Domain\Customer\Data\CustomerLinkedWalletResource;
use Domain\Shared\Data\SusuSchemeResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class DailySusuResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {
        // Return the resource array
        return [
            // Resource type and id
            'type' => 'DailySusu',

            // Resource exposed attributes
            'attributes' => [
                'resource_id' => $this->resource->resource_id,

                'account_name' => $this->resource->account_name,
                'account_number' => $this->resource->account_number,
                'purpose' => $this->resource->purpose,

                'susu_amount' => number_format(num: $this->resource->amount->getAmount()->toFloat(), decimals: 2),
                'initial_deposit' => number_format(num: $this->resource->daily->initial_deposit->getAmount()->toFloat(), decimals: 2),
                'currency' => $this->resource->amount->getCurrency(),

                'frequency' => $this->resource->frequency->code,
                'collection_date' => Carbon::parse($this->resource->daily->savings_duration)->isoFormat(format: 'MM/DD/YYYY'),

                'rollover_enabled' => $this->resource->daily->rollover_enabled,
                'is_collateralized' => $this->resource->daily->is_collateralized,
                'auto_settlement' => $this->resource->daily->auto_settlement,

                'settlement_status' => $this->resource->daily->settlement_status,
                'recurring_debit_status' => $this->resource->daily->recurring_debit_status,
                'status' => $this->resource->status,

                'date_created' => Carbon::parse($this->resource->created_at)->isoFormat(format: 'MM/DD/YYYY'),
            ],
        ];
    }

    public function included(
    ): array {
        return [
            // Included resource
            'scheme' => new SusuSchemeResource(resource: $this->whenLoaded(relationship: 'scheme')),
            'linked_wallet' => CustomerLinkedWalletResource::collection(resource: $this->whenLoaded(relationship: 'wallets')),
//            'account_lock' => $this->when(! empty($this->resource->lock), new SusuAccountLockData($this->resource)),
//            'account_pause' => $this->when(! empty($this->resource->pause), new SusuAccountPauseData($this->resource)),
            'stats' => [
                'collection' => [
                    'type' => 'CollectionStats',
                    'attributes' => [
//                            'total_collections' => $this->resource->daily->stats->total_collections,
//                            'total_successful' => $this->resource->daily->stats->total_successful_collections,
//                            'total_unsuccessful' => $this->resource->daily->stats->total_failed_collections,
                    ],
                ],
                'cycle' => [
                    'type' => 'CycleStats',
                    'attributes' => [
//                            'collection_cycle' => $this->resource->daily->stats->collection_cycle,
//                            'total_cycles' => $this->resource->daily->stats->cycles_completed,
//                            'current_cycle' => $this->resource->daily->stats->current_cycle,
                    ],
                ],
                'settlement' => [
                    'type' => 'SettlementStats',
                    'attributes' => [
//                            'settlement_days' => $this->resource->daily->stats->settlement_days,
//                            'settlement_amount' => number_format($this->resource->daily->stats->settlement_amount->getAmount()->toFloat(), decimals: 2),
//                            'total_settlements' => $this->resource->daily->stats->total_settlements,
//                            'pending_settlements' => $this->resource->daily->stats->pending_settlements,
                    ],
                ],
            ],
        ];
    }
}
