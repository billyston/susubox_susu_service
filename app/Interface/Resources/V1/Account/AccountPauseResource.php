<?php

declare(strict_types=1);

namespace App\Interface\Resources\V1\Account;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class AccountPauseResource extends JsonResource
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
            'type' => 'AccountPause',

            // Resource exposed attributes
            'attributes' => [
                'resource_id' => $this->resource->resource_id,
                'paused_at' => Carbon::parse($this->resource->locked_at)->isoFormat(format: 'MM/DD/YYYY'),
                'resumed_at' => Carbon::parse($this->resource->unlocked_at)->isoFormat(format: 'MM/DD/YYYY'),
                'days_left' => max(now()->startOfDay()->diffInDays(Carbon::parse($this->resource->unlocked_at)->startOfDay()), 0),
                'status' => $this->resource->status,
            ],
        ];
    }
}
