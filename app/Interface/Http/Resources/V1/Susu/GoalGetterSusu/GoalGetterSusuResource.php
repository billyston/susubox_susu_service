<?php

declare(strict_types=1);

namespace App\Interface\Http\Resources\V1\Susu\GoalGetterSusu;

use App\Interface\Http\Resources\V1\Account\AccountResource;
use App\Interface\Http\Resources\V1\Customer\CustomerLinkedWalletResource;
use App\Interface\Http\Resources\V1\Shared\DurationResource;
use App\Interface\Http\Resources\V1\Shared\FrequencyResource;
use App\Interface\Http\Resources\V1\Shared\SusuSchemeResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class GoalGetterSusuResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {
        // Return the resource array
        return [
            // Resource type and id
            'type' => 'GoalGetterSusu',
            'resource_id' => $this->resource->resource_id,

            // Resource exposed attributes
            'attributes' => [
                'target_amount' => number_format(num: $this->resource->target_amount->getAmount()->toFloat(), decimals: 2),
                'rollover_enabled' => $this->resource->rollover_enabled,
                'is_collateralized' => $this->resource->is_collateralized,
                'recurring_debit_status' => $this->resource->recurring_debit_status,
                'withdrawal_status' => $this->resource->withdrawal_status,
            ],

            // Included resource
            'included' => [
                'account' => new AccountResource($this->resource->account),
                'linked_wallet' => CustomerLinkedWalletResource::collection($this->resource->account->wallets),
                'frequency' => new FrequencyResource($this->resource->account->frequency),
                'scheme' => new SusuSchemeResource($this->resource->account->scheme),
                'duration' => new DurationResource($this->resource->account->goal->duration),
//                'account_lock' => $this->when(! empty($this->resource->lock), new SusuAccountLockData($this->resource)),
//                'account_pause' => $this->when(! empty($this->resource->pause), new SusuAccountPauseData($this->resource)),
            ],
        ];
    }
}
