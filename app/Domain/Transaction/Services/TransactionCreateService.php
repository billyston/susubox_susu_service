<?php

declare(strict_types=1);

namespace App\Domain\Transaction\Services;

use App\Application\Transaction\DTOs\TransactionCreateDTO;
use App\Domain\Account\Models\Account;
use App\Domain\Customer\Models\LinkedWallet;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Transaction\Models\Transaction;
use App\Domain\Transaction\Models\TransactionCategory;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class TransactionCreateService
{
    /**
     * @throws SystemFailureException
     */
    public function execute(
        Account $account,
        ?LinkedWallet $linkedWallet,
        TransactionCategory $transactionCategory,
        TransactionCreateDTO $data
    ): Transaction {
        try {
            // Execute the database transaction
            return DB::transaction(function () use (
                $account,
                $linkedWallet,
                $transactionCategory,
                $data
            ) {
                // Create and return the Transaction resource
                return Transaction::query()->updateOrCreate([
                    'reference_number' => $data->reference_number,
                ], [
                    'account_id' => $account->id,
                    'transaction_category_id' => $transactionCategory->id,
                    'linked_wallet_id' => $linkedWallet->id ?? null,
                    'reference_number' => $data->reference_number,
                    'amount' => $data->amount,
                    'charge' => $data->charges,
                    'total' => $data->total,
                    'description' => $data->description,
                    'narration' => Transaction::narration(
                        category: $transactionCategory,
                        amount: $data->amount->getAmount()->toFloat(),
                        account_number: $account->account_number,
                        wallet: $data->wallet_number,
                        date: $data->date,
                    ),
                    'date' => $data->date,
                    'extra_data' => ['is_initial_deposit' => $data->is_initial_deposit],
                    'status_code' => $data->status_code,
                    'status' => $data->status,
                ])->refresh();
            });
        } catch (
            QueryException $queryException
        ) {
            throw $queryException;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in TransactionCreateService', [
                'account' => $account,
                'linked_wallet' => $linkedWallet,
                'transaction_category' => $transactionCategory,
                'dto' => $data,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was an error while trying to create the transaction.',
            );
        }
    }
}
