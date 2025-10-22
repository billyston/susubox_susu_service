<?php

declare(strict_types=1);

namespace App\Interface\Http\Resources\V1\Shared;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class DateResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {
        return [
            'human' => $this->resource->diffForHumans(),
            'string' => $this->resource->toIso8601String(),
        ];
    }
}
