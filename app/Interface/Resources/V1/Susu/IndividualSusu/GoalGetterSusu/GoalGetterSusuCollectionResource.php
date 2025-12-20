<?php

declare(strict_types=1);

namespace App\Interface\Resources\V1\Susu\IndividualSusu\GoalGetterSusu;

use App\Interface\Resources\V1\Account\AccountResource;
use App\Interface\Resources\V1\Customer\CustomerWalletResource;
use App\Interface\Resources\V1\Shared\DurationResource;
use App\Interface\Resources\V1\Shared\FrequencyResource;
use App\Interface\Resources\V1\Shared\SusuSchemeResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class GoalGetterSusuCollectionResource extends JsonResource
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
            'type' => 'GoalGetterSusu',

            // Resource exposed attributes
            'attributes' => [
                'resource_id' => $this->resource->resource_id,
                'target_amount' => $this->resource->target_amount->getAmount()->__toString(),
                'susu_amount' => $this->resource->susu_amount->getAmount()->__toString(),
                'initial_deposit' => $this->resource->initial_deposit->getAmount()->__toString(),
                'currency' => $this->resource->susu_amount->getCurrency()->__toString(),
                'rollover_enabled' => $this->resource->rollover_enabled,
                'is_collateralized' => $this->resource->is_collateralized,
                'recurring_debit_status' => $this->resource->recurring_debit_status,
                'withdrawal_status' => $this->resource->withdrawal_status,
            ],

            // Relationships
            'relationships' => [
                'account' => new AccountResource($this->resource->individual->account),
                'wallet' => new CustomerWalletResource($this->resource->wallet),
                'duration' => new DurationResource($this->resource->duration),
                'frequency' => new FrequencyResource($this->resource->frequency),
                'scheme' => new SusuSchemeResource($this->resource->individual->susuScheme),

//                'account_lock' => $this->when(! empty($this->resource->lock), new SusuAccountLockData($this->resource)),
//                'account_pause' => $this->when(! empty($this->resource->pause), new SusuAccountPauseData($this->resource)),
            ],
        ];
    }
}
