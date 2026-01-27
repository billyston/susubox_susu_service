<?php

declare(strict_types=1);

namespace App\Interface\Resources\V1\Account\AccountStats;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class AccountTransactionStatsResource extends JsonResource
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
            'type' => 'AccountTransactionStats',

            // Resource exposed attributes
            'attributes' => [
                'total_transactions' => $this->resource->total_transactions,
                'credit_transaction_count' => $this->resource->credit_transaction_count,
                'debit_transaction_count' => $this->resource->debit_transaction_count,
                'successful_transactions' => $this->resource->successful_transactions,
                'failed_transactions' => $this->resource->failed_transactions,
                'reversed_transactions' => $this->resource->reversed_transactions,
                'total_credit_amount' => $this->resource->total_credit_amount->getAmount()->__toString(),
                'total_debit_amount' => $this->resource->total_debit_amount->getAmount()->__toString(),
                'net_transaction_balance' => $this->resource->net_transaction_balance->getAmount()->__toString(),
                'first_transaction_date' => $this->resource->first_transaction_date,
                'last_transaction_date' => $this->resource->last_transaction_date,
                'last_successful_transaction_date' => $this->resource->last_successful_transaction_date,
                'last_failed_transaction_date' => $this->resource->last_failed_transaction_date,
            ],
        ];
    }
}
