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
                'target_amount' => number_format(num: $this->resource->target_amount->getAmount()->toFloat(), decimals: 2),
                'rollover_enabled' => $this->resource->rollover_enabled,
                'is_collateralized' => $this->resource->is_collateralized,
                'recurring_debit_status' => $this->resource->recurring_debit_status,
                'withdrawal_status' => $this->resource->withdrawal_status,
            ],

            // Relationships
            'relationships' => [
                'account' => new AccountResource($this->resource->account),
                'linked_wallet' => CustomerWalletResource::collection($this->resource->account->wallets),
                'frequency' => new FrequencyResource($this->resource->account->frequency),
                'duration' => new DurationResource($this->resource->account->goal->duration),
                'scheme' => new SusuSchemeResource($this->resource->account->scheme),
            ],
        ];
    }
}
