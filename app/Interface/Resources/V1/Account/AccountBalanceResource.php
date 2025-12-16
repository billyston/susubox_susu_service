<?php

declare(strict_types=1);

namespace App\Interface\Resources\V1\Account;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class AccountBalanceResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {
        // Get the main resources
        $available = $this->resource->available_balance;

        // Build and return the resource array
        return [
            // Resource type and id
            'type' => 'AccountBalance',

            // Resource exposed attributes
            'attributes' => [
                'available_balance' => $available->getAmount()->__toString(),
                'currency' => $available->getCurrency()->getCurrencyCode(),
            ],
        ];
    }
}
