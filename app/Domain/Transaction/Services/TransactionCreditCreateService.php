<?php

declare(strict_types=1);

namespace App\Domain\Transaction\Services;

use App\Application\Transaction\DTOs\TransactionCreateRequestDTO;
use App\Application\Transaction\Events\TransactionCreditCreatedEvent;
use App\Domain\Account\Models\AccountBalance;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Transaction\Models\Transaction;
use Brick\Money\Exception\MoneyMismatchException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class TransactionCreditCreateService
{
    private TransactionCreateService $transactionCreateService;

    /**
     * @param TransactionCreateService $transactionCreateService
     */
    public function __construct(
        TransactionCreateService $transactionCreateService,
    ) {
        $this->transactionCreateService = $transactionCreateService;
    }

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

                // Return the Transaction (if its already exist)
                if ($existingTransaction !== null) {
                    return $existingTransaction;
                }

                // Create the new Transaction
                $transaction = $this->transactionCreateService->execute(
                    paymentInstruction: $paymentInstruction,
                    requestDTO: $requestDTO
                );

                // Fire the TransactionCreditCreatedEvent
                DB::afterCommit(function () use ($transaction) {
                    event(new TransactionCreditCreatedEvent(
                        transactionResourceId: $transaction->resource_id
                    ));
                });

                // (Guard) Return the Transaction if new transaction status is not (success)
                if ($transaction->status !== Statuses::SUCCESS->value) {
                    return $transaction;
                }

                // Find the AccountBalance and lock it for balance update
                $this->accountBalanceUpdate(
                    transaction: $transaction
                );

                // Return the Transaction resource
                return $transaction->refresh();
            });
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
                message: 'There was a system failure while trying to create the transaction.',
            );
        }
    }

    /**
     * @param Transaction $transaction
     * @return void
     * @throws MoneyMismatchException
     */
    private function accountBalanceUpdate(
        Transaction $transaction
    ): void {
        // Get the AccountBalance
        $balance = AccountBalance::query()
            ->where('account_id', $transaction->account_id)
            ->lockForUpdate()
            ->firstOrFail();

        // Set the new balance data
        $balance->ledger_balance = $balance->ledger_balance->plus($transaction->amount);
        $balance->available_balance = $balance->available_balance->plus($transaction->total);
        $balance->last_transaction_id = $transaction->id;
        $balance->last_reconciled_at = now();

        // Save the data in the database
        $balance->save();
    }
}
