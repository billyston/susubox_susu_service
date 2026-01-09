<?php

declare(strict_types=1);

namespace App\Interface\Resources\V1\Susu\IndividualSusu\DailySusu;

use App\Interface\Resources\V1\Account\AccountLockResource;
use App\Interface\Resources\V1\Account\AccountPauseResource;
use App\Interface\Resources\V1\Shared\SusuSchemeResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class DailySusuResource extends JsonResource
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
            'attributes' => [
                'resource_id' => $this->resource->resource_id,
                'frequency' => $this->resource->frequency->code,
                'susu_amount' => $this->resource->susu_amount->getAmount()->__toString(),
                'initial_deposit' => $this->resource->initial_deposit->getAmount()->__toString(),
                'initial_deposit_frequency' => $this->resource->initial_deposit_frequency,
                'rollover_enabled' => $this->resource->rollover_enabled,
                'is_collateralized' => $this->resource->is_collateralized,
                'auto_settlement' => $this->resource->auto_settlement,
                'settlement_status' => $this->resource->settlement_status,
                'recurring_debit_status' => $this->resource->recurring_debit_status,
            ],

            // Included resource
            'included' => [
                'account' => [
                    'type' => 'Account',
                    'attributes' => [
                        'resource_id' => $this->resource->individual->account->resource_id,
                        'account_name' => $this->resource->individual->account->account_name,
                        'account_number' => $this->resource->individual->account->account_number,
                        'account_activity_period' => Carbon::parse($this->resource->individual->account->account_activity_period)->toDateTimeString(),
                        'status' => $this->resource->individual->account->status,
                        'date_created' => Carbon::parse($this->resource->individual->account->created_at)->toDateTimeString(),
                    ],
                ],
                'account_cycle_definition' => [
                    'type' => 'AccountCycleDefinition',
                    'attributes' => [
                        'cycle_length' => $this->resource->cycleDefinition->cycle_length,
                        'commission_frequencies' => $this->resource->cycleDefinition->commission_frequencies,
                        'settlement_frequencies' => $this->resource->cycleDefinition->settlement_frequencies,
                        'expected_frequencies' => $this->resource->cycleDefinition->expected_frequencies,

                        'expected_cycle_amount' => $this->resource->cycleDefinition->expected_cycle_amount->getAmount()->__toString(),
                        'expected_settlement_amount' => $this->resource->cycleDefinition->expected_settlement_amount->getAmount()->__toString(),
                        'commission_amount' => $this->resource->cycleDefinition->commission_amount->getAmount()->__toString(),
                    ],
                ],
                'account_cycles' => DailySusuAccountCycleResource::collection($this->resource->cycles),
                'wallet' => [
                    'type' => 'Wallet',
                    'attributes' => [
                        'resource_id' => $this->resource->wallet->resource_id,
                        'wallet_name' => $this->resource->wallet->wallet_name,
                        'wallet_number' => $this->resource->wallet->wallet_number,
                        'wallet_network' => $this->resource->wallet->network_code,
                        'wallet_status' => $this->resource->wallet->status,
                    ],
                ],
                'susu_scheme' => new SusuSchemeResource($this->resource->individual->scheme),
                'account_lock' => $this->when($this->resource->isLocked(), new AccountLockResource($this->resource->activeAccountLock())),
                'account_pause' => $this->when($this->resource->isPaused(), new AccountPauseResource($this->resource->activeAccountPause())),
            ],
        ];
    }
}
