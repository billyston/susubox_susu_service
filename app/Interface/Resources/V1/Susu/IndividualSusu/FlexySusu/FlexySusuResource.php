<?php

declare(strict_types=1);

namespace App\Interface\Resources\V1\Susu\IndividualSusu\FlexySusu;

use App\Interface\Resources\V1\Account\AccountResource;
use App\Interface\Resources\V1\Customer\CustomerWalletResource;
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
            'resource_id' => $this->resource->resource_id,

            // Resource exposed attributes
            'attributes' => [
                'is_collateralized' => $this->resource->is_collateralized,
                'withdrawal_status' => $this->resource->withdrawal_status,
            ],

            // Included resource
            'included' => [
                'account' => new AccountResource($this->resource->individual->account),
                'wallet' => new CustomerWalletResource($this->resource->wallet),

//                'susu_scheme' => new SusuSchemeResource($this->resource->individualAccount->susuScheme),
//                'account_lock' => $this->when(! empty($this->resource->lock), new SusuAccountLockData($this->resource)),
//                'account_pause' => $this->when(! empty($this->resource->pause), new SusuAccountPauseData($this->resource)),
            ],
        ];
    }
}
