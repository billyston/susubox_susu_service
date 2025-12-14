<?php

declare(strict_types=1);

namespace App\Domain\Transaction\Services;

use App\Application\Transaction\DTOs\TransactionCreateRequestDTO;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Transaction\Models\Transaction;
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
        PaymentInstruction $payment_instruction,
        TransactionCreateRequestDTO $dto
    ): Transaction {
        try {
            // Execute the database transaction
            return DB::transaction(function () use (
                $payment_instruction,
                $dto
            ) {
                // Create and return the Transaction resource
                return Transaction::query()->updateOrCreate([
                    'reference_number' => $dto->reference,
                ], [
                    'resource_id' => $dto->resource_id,
                    'account_id' => $payment_instruction->account_id,
                    'payment_instruction_id' => $payment_instruction->id,
                    'transaction_category_id' => $payment_instruction->transaction_category_id,
                    'wallet_id' => $payment_instruction->wallet->id,
                    'reference_number' => $dto->reference,
                    'amount' => $dto->amount,
                    'charge' => $dto->charges,
                    'total' => $dto->total,
                    'description' => $dto->description,
                    'narration' => Transaction::narration(
                        category: $payment_instruction->transactionCategory->name,
                        amount: $dto->amount->getAmount()->toFloat(),
                        account_number: $payment_instruction->account->account_number,
                        wallet: $payment_instruction->wallet->wallet_number,
                        date: $dto->date,
                    ),
                    'date' => $dto->date,
                    'status_code' => $dto->code,
                    'status' => $dto->status,
                    'extra_data' => $dto->toArray() ?? null,
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
                'payment_instruction' => $payment_instruction,
                'dto' => $dto,
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
