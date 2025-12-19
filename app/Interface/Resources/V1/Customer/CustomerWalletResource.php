<?php

declare(strict_types=1);

namespace App\Interface\Resources\V1\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class CustomerWalletResource extends JsonResource
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
            'type' => 'Wallet',

            // Resource exposed attributes
            'attributes' => [
                'resource_id' => $this->resource->resource_id,
                'wallet_name' => $this->resource->wallet_name,
                'wallet_number' => $this->resource->wallet_number,
                'wallet_network' => $this->resource->network_code,
                'wallet_status' => $this->resource->status,
                'wallet_added_at' => $this->resource->created_at->diffForHumans(),
            ],
        ];
    }
}
