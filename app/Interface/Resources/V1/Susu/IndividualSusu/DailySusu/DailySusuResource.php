<?php

declare(strict_types=1);

namespace App\Interface\Resources\V1\Susu\IndividualSusu\DailySusu;

use App\Interface\Resources\V1\Account\AccountResource;
use App\Interface\Resources\V1\Customer\CustomerWalletResource;
use App\Interface\Resources\V1\Shared\SusuSchemeResource;
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
                'susu_amount' => $this->resource->susu_amount->getAmount()->__toString(),
                'initial_deposit' => $this->resource->initial_deposit->getAmount()->__toString(),
                'frequency' => $this->resource->frequency->code,
                'rollover_enabled' => $this->resource->rollover_enabled,
                'is_collateralized' => $this->resource->is_collateralized,
                'auto_settlement' => $this->resource->auto_settlement,
                'settlement_status' => $this->resource->settlement_status,
                'recurring_debit_status' => $this->resource->recurring_debit_status,
            ],

            // Included resource
            'included' => [
                'account' => new AccountResource($this->resource->individual->account),
                'wallet' => new CustomerWalletResource($this->resource->wallet),
                'susu_scheme' => new SusuSchemeResource($this->resource->individual->susuScheme),

//                'account_lock' => $this->when(! empty($this->resource->lock), new SusuAccountLockData($this->resource)),
//                'account_pause' => $this->when(! empty($this->resource->pause), new SusuAccountPauseData($this->resource)),
            ],
        ];
    }
}
