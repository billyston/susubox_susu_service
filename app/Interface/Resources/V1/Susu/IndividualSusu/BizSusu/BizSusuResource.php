<?php

declare(strict_types=1);

namespace App\Interface\Resources\V1\Susu\IndividualSusu\BizSusu;

use App\Interface\Resources\V1\Account\AccountResource;
use App\Interface\Resources\V1\Customer\CustomerWalletResource;
use App\Interface\Resources\V1\Shared\SusuSchemeResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class BizSusuResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray(
        Request $request
    ): array {
        // Build and return the resource array
        return [
            // Resource type and id
            'type' => 'BizSusu',

            // Resource exposed attributes
            'attributes' => [
                'resource_id' => $this->resource->resource_id,
                'susu_amount' => $this->resource->susu_amount->getAmount()->__toString(),
                'initial_deposit' => $this->resource->initial_deposit->getAmount()->__toString(),
                'frequency' => $this->resource->frequency->code,
                'rollover_enabled' => $this->resource->rollover_enabled,
                'is_collateralized' => $this->resource->is_collateralized,
                'withdrawal_status' => $this->resource->withdrawal_status,
                'recurring_debit_status' => $this->resource->recurring_debit_status,
            ],

            // Included resource
            'included' => [
                'account' => new AccountResource($this->resource->individual->account),
                'wallet' => new CustomerWalletResource($this->resource->wallet),
                'susu_scheme' => new SusuSchemeResource($this->resource->individual->susuScheme),

//                'susu_scheme' => new SusuSchemeResource($this->resource->individualAccount->susuScheme),
//                'account_lock' => $this->when(! empty($this->resource->lock), new SusuAccountLockData($this->resource)),
//                'account_pause' => $this->when(! empty($this->resource->pause), new SusuAccountPauseData($this->resource)),
            ],
        ];
    }
}
