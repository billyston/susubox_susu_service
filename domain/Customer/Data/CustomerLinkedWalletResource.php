<?php

declare(strict_types=1);

namespace Domain\Customer\Data;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class CustomerLinkedWalletResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {
        return [
            // Resource type and id
            'type' => 'LinkedWallet',
            'resource_id' => $this->resource->resource_id,

            // Resource exposed attributes
            'attributes' => [
                'wallet_name' => $this->resource->wallet_name,
                'wallet_number' => $this->resource->wallet_number,
                'status' => $this->resource->status,
                'linked_at' => $this->resource->created_at->diffForHumans(),
            ],
        ];
    }
}
