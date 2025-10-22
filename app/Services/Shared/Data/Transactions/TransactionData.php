<?php

declare(strict_types=1);

namespace App\Services\Shared\Data\Transactions;

use App\Domain\Transaction\Models\Transaction;

final class TransactionData
{
    public static function toArray(
        Transaction $transaction,
    ): array {
        // Prepare and return the data
        return [
            // Transaction main data
            'data' => [
                'type' => 'Transaction',
                'resource_id' => $transaction->resource_id,
                'attributes' => [
                    'reference_number' => $transaction->reference_number,
                    'is_initial_deposit' => $transaction->extra_data['is_initial_deposit'],
                    'transaction_category' => $transaction->category->code,
                    'amount' => $transaction->amount->getAmount(),
                    'charge' => $transaction->charge->getAmount(),
                    'total' => $transaction->total->getAmount(),
                    'description' => $transaction->description,
                    'date' => $transaction->date,
                    'status' => $transaction->status,
                ],
            ],

            // Included data
            'included' => [
                'account' => [
                    'type' => 'Account',
                    'resource_id' => $transaction->account->resource_id,
                    'attributes' => [
                        'account_scheme' => $transaction->account->scheme->name,
                        'account_name' => $transaction->account->account_name,
                        'account_number' => $transaction->account->account_number,
                    ],
                ],
                'customer' => [
                    'type' => 'Customer',
                    'resource_id' => $transaction->account->customer->resource_id,
                    'attributes' => [
                        'phone_number' => $transaction->account->customer->phone_number,
                    ],
                ],
                'wallet' => [
                    'type' => 'LinkedWallet',
                    'resource_id' => $transaction->wallet->resource_id,
                    'attributes' => [
                        'wallet_name' => $transaction->wallet->wallet_name,
                        'wallet_number' => $transaction->wallet->wallet_number,
                    ],
                ],
            ],
        ];
    }
}
