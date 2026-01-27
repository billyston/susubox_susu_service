<?php

declare(strict_types=1);

namespace App\Interface\Resources\V1\Account\AccountStats;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class AccountTransactionGapResource extends JsonResource
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
            'type' => 'AccountTransactionGapStats',

            // Resource exposed attributes
            'attributes' => [
                'longest_transaction_gap_days' => $this->resource->longest_transaction_gap_days,
                'longest_credit_gap_days' => $this->resource->longest_credit_gap_days,
                'longest_debit_gap_days' => $this->resource->longest_debit_gap_days,
            ],
        ];
    }
}
