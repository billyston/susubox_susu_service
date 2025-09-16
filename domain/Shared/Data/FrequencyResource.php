<?php

declare(strict_types=1);

namespace Domain\Shared\Data;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class FrequencyResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {
        return [
            // Resource type and id
            'type' => 'Frequency',

            // Resource exposed attributes
            'attributes' => [
                'resource_id' => $this->resource->resource_id,
                'name' => $this->resource->name,
                'alias' => $this->resource->alias,
                'code' => $this->resource->code,
                'description' => $this->resource->description,
            ],
        ];
    }
}
