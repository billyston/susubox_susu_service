<?php

declare(strict_types=1);

namespace App\Domain\Transaction\Services;

use App\Application\Transaction\DTOs\TransactionCreateRequestDTO;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Transaction\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class TransactionCreateService
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
                // Create the Transaction and return the resource
                return Transaction::create([
                    'resource_id' => $requestDTO->resourceID,
                    'account_id' => $paymentInstruction->account_id,
                    'payment_instruction_id' => $paymentInstruction->id,
                    'transaction_category_id' => $paymentInstruction->transaction_category_id,
                    'wallet_id' => $paymentInstruction->wallet->id,
                    'transaction_type' => $paymentInstruction->transaction_type,
                    'reference_number' => $requestDTO->reference,
                    'amount' => $requestDTO->amount,
                    'charge' => $requestDTO->charges,
                    'total' => $requestDTO->total,
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
                    'metadata' => $requestDTO->toArray(),
                ]);
            });
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in TransactionCreateService', [
                'payment_instruction' => $paymentInstruction,
                'request_dto' => $requestDTO,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                    'code' => $throwable->getCode(),
                    'trace' => $throwable->getTraceAsString(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was an error while trying to create the transaction.',
            );
        }
    }
}
