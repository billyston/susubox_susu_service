<?php

declare(strict_types=1);

namespace App\Interface\Resources\V1\Shared;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class SusuSchemeResource extends JsonResource
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
            'type' => 'SusuScheme',

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
