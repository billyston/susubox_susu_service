<?php

declare(strict_types=1);

namespace App\Interface\Resources\V1\Shared;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class SusuSchemeResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {
        return [
            // Resource type and id
            'type' => 'SusuScheme',
            'resource_id' => $this->resource->resource_id,

            // Resource exposed attributes
            'attributes' => [
                'name' => $this->resource->name,
                'alias' => $this->resource->alias,
                'code' => $this->resource->code,
                'description' => $this->resource->description,
            ],
        ];
    }
}
