<?php

declare(strict_types=1);

namespace App\Domain\Transaction\Services;

use App\Application\Transaction\DTOs\TransactionCreateRequestDTO;
use App\Domain\Account\Models\AccountBalance;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Transaction\Models\Transaction;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class TransactionCreateCreditService
{
    /**
     * @param PaymentInstruction $paymentInstruction
     * @param TransactionCreateRequestDTO $requestDTO
     * @return Transaction
     * @throws SystemFailureException
     */
    public function execute(
        PaymentInstruction $paymentInstruction,
        TransactionCreateRequestDTO $requestDTO
    ): Transaction {
        try {
            // Execute the database transaction
            return DB::transaction(function () use (
                $paymentInstruction,
                $requestDTO
            ) {
                // Find Transaction (if its already exist: Idempotency)
                $existingTransaction = Transaction::query()
                    ->where('reference_number', $requestDTO->reference)
                    ->lockForUpdate()
                    ->first();

                // Return the Transaction if exist (and abort process)
                if ($existingTransaction) {
                    return $existingTransaction;
                }

                // Create the new Transaction
                $transaction = Transaction::create([
                    'resource_id' => $requestDTO->resourceID,
                    'account_id' => $paymentInstruction->account_id,
                    'payment_instruction_id' => $paymentInstruction->id,
                    'transaction_category_id' => $paymentInstruction->transaction_category_id,
                    'wallet_id' => $paymentInstruction->wallet->id,
                    'transaction_type' => $paymentInstruction->transaction_type,
                    'reference_number' => $requestDTO->reference,

                    'amount' => $paymentInstruction->amount,
                    'charge' => $paymentInstruction->charge,
                    'total' => $paymentInstruction->total,

                    'description' => $requestDTO->description,
                    'narration' => Transaction::narration(
                        category: $paymentInstruction->transactionCategory->name,
                        amount: $requestDTO->amount->getAmount()->toFloat(),
                        account_number: $paymentInstruction->account->account_number,
                        wallet: $paymentInstruction->wallet->wallet_number,
                        date: $requestDTO->date,
                    ),
                    'date' => $requestDTO->date,
                    'status_code' => $requestDTO->code,
                    'status' => $requestDTO->status,
                    'extra_data' => $requestDTO->toArray(),
                ]);

                // Return the Transaction if new transaction status is not (success)
                if ($transaction->status !== $requestDTO->status) {
                    return $transaction;
                }

                // Find the AccountBalance and lock it for balance update
                $balance = AccountBalance::query()
                    ->where('account_id', $paymentInstruction->account_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                // Set the new balance data
                $balance->ledger_balance = $balance->ledger_balance->plus($paymentInstruction->amount);
                $balance->available_balance = $balance->available_balance->plus($paymentInstruction->amount);
                $balance->last_transaction_id = $transaction->id;
                $balance->last_reconciled_at = now();

                // Execute and save to the database
                $balance->save();

                // Return the Transaction resource
                return $transaction->refresh();
            });
        } catch (
            QueryException $queryException
        ) {
            throw $queryException;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in TransactionCreateCreditService', [
                'paymentInstruction' => $paymentInstruction,
                'requestDto' => $requestDTO,
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
