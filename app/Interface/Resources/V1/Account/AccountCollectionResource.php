<?php

declare(strict_types=1);

namespace App\Interface\Resources\V1\Account;

use App\Interface\Resources\V1\Shared\SusuSchemeResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class AccountCollectionResource extends JsonResource
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
            'type' => 'Account',

            // Resource exposed attributes
            'attributes' => [
                'resource_id' => $this->resource->resource_id,
                'account_name' => $this->resource->account_name,
                'account_number' => $this->resource->account_number,
                'account_type' => $this->resource->account_type,
                'status' => $this->resource->status,
                'date_created' => Carbon::parse($this->resource->created_at)->toDateTimeString(),
            ],

            // Relationships
            'relationships' => [
                'susu_scheme' => new SusuSchemeResource($this->resource->susuScheme),
            ],
        ];
    }
}
