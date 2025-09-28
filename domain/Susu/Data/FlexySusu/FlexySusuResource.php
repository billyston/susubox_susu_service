<?php

declare(strict_types=1);

namespace Domain\Susu\Data\FlexySusu;

use Domain\Customer\Data\CustomerLinkedWalletResource;
use Domain\Shared\Data\FrequencyResource;
use Domain\Shared\Data\SusuSchemeResource;
use Domain\Susu\Data\Account\AccountResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class FlexySusuResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {
        // Return the resource array
        return [
            // Resource type and id
            'type' => 'FlexySusu',

            // Resource exposed attributes
            'attributes' => [
                'initial_deposit' => number_format(num: $this->resource->initial_deposit->getAmount()->toFloat(), decimals: 2),
                'is_collateralized' => $this->resource->is_collateralized,
                'withdrawal_status' => $this->resource->withdrawal_status,
            ],
        ];
    }

    public function included(
    ): array {
        return [
            // Included resource
            'account' => new AccountResource($this->resource->account),
            'frequency' => optional(new FrequencyResource($this->resource->account->frequency)),
//            'frequency' => $this->when(! empty($this->resource->account->frequency), new FrequencyResource($this->resource->account->frequency)),
            'scheme' => new SusuSchemeResource($this->resource->account->scheme),
            'linked_wallet' => CustomerLinkedWalletResource::collection($this->resource->account->wallets),
//            'account_lock' => $this->when(! empty($this->resource->lock), new SusuAccountLockData($this->resource)),
//            'account_pause' => $this->when(! empty($this->resource->pause), new SusuAccountPauseData($this->resource)),
        ];
    }
}
