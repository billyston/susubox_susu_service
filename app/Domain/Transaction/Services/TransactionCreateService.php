<?php

declare(strict_types=1);

namespace App\Domain\Transaction\Services;

use App\Domain\Account\Models\Account;
use App\Domain\Customer\Models\LinkedWallet;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Transaction\Models\Transaction;
use App\Domain\Transaction\Models\TransactionCategory;
use Brick\Money\Money;
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
        array $request_data
    ): Transaction {
        try {
            // Execute the database transaction
            return DB::transaction(function () use (
                $account,
                $linkedWallet,
                $transactionCategory,
                $request_data
            ) {
                // Extract and create the Money values
                $amount = Money::of($request_data['amount'], currency: 'GHS');
                $charge = Money::of($request_data['charges'], currency: 'GHS');
                $total = Money::of($request_data['total'], currency: 'GHS');

                // Create and return the Transaction resource
                return Transaction::updateOrCreate([
                    'reference_number' => $request_data['reference_number'],
                ], [
                    'account_id' => $account->id,
                    'transaction_category_id' => $transactionCategory->id,
                    'linked_wallet_id' => $linkedWallet->id ?? null,
                    'reference_number' => $request_data['reference_number'],
                    'amount' => $amount,
                    'charge' => $charge,
                    'total' => $total,
                    'description' => $request_data['description'],
                    'narration' => Transaction::narration(
                        category: $transactionCategory,
                        amount: $request_data['amount'],
                        account_number: $account->account_number,
                        wallet: $request_data['wallet'],
                        date: $request_data['date'],
                    ),
                    'date' => $request_data['date'],
                    'extra_data' => ['is_initial_deposit' => $request_data['is_initial_deposit']],
                    'status_code' => $request_data['status_code'],
                    'status' => $request_data['status'],
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
                'transaction_category' => $transactionCategory,
                'request_data' => $request_data,
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
