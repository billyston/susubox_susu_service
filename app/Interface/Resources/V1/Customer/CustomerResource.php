<?php

declare(strict_types=1);

namespace App\Interface\Resources\V1\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class CustomerResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {
        return [
            // Resource type and id
            'type' => 'Customer',

            // Resource exposed attributes
            'attributes' => [
                'resource_id' => $this->resource->resource_id,
                'phone_number' => $this->resource->phone_number,
                'created_at' => $this->resource->created_at,
            ],
        ];
    }
}
