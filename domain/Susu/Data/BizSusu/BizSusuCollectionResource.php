<?php

declare(strict_types=1);

namespace Domain\Susu\Data\BizSusu;

use Domain\Customer\Data\CustomerLinkedWalletResource;
use Domain\Shared\Data\FrequencyResource;
use Domain\Shared\Data\SusuSchemeResource;
use Domain\Susu\Data\Account\AccountResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class BizSusuCollectionResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {
        // Return the resource array
        return [
            // Resource type and id
            'type' => 'BizSusu',
            'resource_id' => $this->resource->resource_id,

            // Resource exposed attributes
            'attributes' => [
                'rollover_enabled' => $this->resource->rollover_enabled,
                'is_collateralized' => $this->resource->is_collateralized,
                'withdrawal_status' => $this->resource->withdrawal_status,
                'recurring_debit_status' => $this->resource->recurring_debit_status,
            ],

            // Relationships
            'relationships' => [
                'account' => new AccountResource($this->resource->account),
                'linked_wallet' => CustomerLinkedWalletResource::collection($this->resource->account->wallets),
                'frequency' => new FrequencyResource($this->resource->account->frequency),
                'scheme' => new SusuSchemeResource($this->resource->account->scheme),
            ],
        ];
    }
}
