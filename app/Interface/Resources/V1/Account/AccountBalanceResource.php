<?php

declare(strict_types=1);

namespace App\Interface\Resources\V1\Account;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class AccountBalanceResource extends JsonResource
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
            'type' => 'AccountBalance',

            // Resource exposed attributes
            'attributes' => [
                'available_balance' => $this->resource->available_balance->getAmount()->__toString(),
                'currency' => $this->resource->available_balance->getCurrency()->getCurrencyCode(),
            ],
        ];
    }
}
