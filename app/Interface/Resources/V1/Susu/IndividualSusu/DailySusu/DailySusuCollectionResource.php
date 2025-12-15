<?php

declare(strict_types=1);

namespace App\Interface\Resources\V1\Susu\IndividualSusu\DailySusu;

use App\Interface\Resources\V1\Account\AccountResource;
use App\Interface\Resources\V1\Customer\CustomerWalletResource;
use App\Interface\Resources\V1\Shared\FrequencyResource;
use App\Interface\Resources\V1\Shared\SusuSchemeResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class DailySusuCollectionResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {
        // Return the resource array
        return [
            // Resource type and id
            'type' => 'DailySusu',
            'resource_id' => $this->resource->resource_id,

            // Resource exposed attributes
            'attributes' => [
                'rollover_enabled' => $this->resource->rollover_enabled,
                'is_collateralized' => $this->resource->is_collateralized,
                'auto_settlement' => $this->resource->auto_settlement,
                'settlement_status' => $this->resource->settlement_status,
                'recurring_debit_status' => $this->resource->recurring_debit_status,
            ],

            // Relationships
            'relationships' => [
                'account' => new AccountResource($this->resource->account),
                'linked_wallet' => CustomerWalletResource::collection($this->resource->account->wallets),
                'frequency' => new FrequencyResource($this->resource->account->frequency),
                'scheme' => new SusuSchemeResource($this->resource->account->scheme),
            ],
        ];
    }
}
