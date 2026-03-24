<?php

declare(strict_types=1);

namespace App\Application\Transaction\Actions;

use App\Domain\PaymentInstruction\Services\PaymentInstruction\PaymentInstructionStatusUpdateService;
use App\Domain\PaymentInstruction\Services\RecurringDeposit\RecurringDepositStatusUpdateService;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Transaction\Models\Transaction;

final readonly class TransactionCreatedFailureAction
{
    /**
     * @param RecurringDepositStatusUpdateService $recurringDebitStatusUpdateService
     * @param PaymentInstructionStatusUpdateService $paymentInstructionStatusUpdateService
     */
    public function __construct(
        private RecurringDepositStatusUpdateService $recurringDebitStatusUpdateService,
        private PaymentInstructionStatusUpdateService $paymentInstructionStatusUpdateService,
    ) {
        // ..
    }

    /**
     * @param Transaction $transaction
     * @return void
     * @throws SystemFailureException
     */
    public function execute(
        Transaction $transaction,
    ): void {
        $this->paymentInstructionStatusUpdate(transaction: $transaction);
        $this->recurringDebitStatusUpdateHandler(transaction: $transaction);

        // Other post-transaction actions go here
    }

    /**
     * @throws SystemFailureException
     */
    private function paymentInstructionStatusUpdate(
        Transaction $transaction
    ): void {
        // Get the PaymentInstruction from Transaction
        $paymentInstruction = $transaction->paymentInstruction;

        // Execute the PaymentInstructionStatusUpdateService (if 'status' not 'success')
        if ($paymentInstruction->status !== Statuses::SUCCESS->value) {
            $this->paymentInstructionStatusUpdateService->execute(
                paymentInstruction: $paymentInstruction,
                status: Statuses::FAILED->value,
            );
        }
    }

    /**
     * @param Transaction $transaction
     * @return void
     * @throws SystemFailureException
     */
    private function recurringDebitStatusUpdateHandler(
        Transaction $transaction
    ): void {
        // Get the PaymentInstruction, RecurringDeposit from Transaction
        $paymentInstruction = $transaction->paymentInstruction;
        $recurringDeposit = $paymentInstruction->recurringDeposit;

        // Safely get metadata
        $metadata = $transaction->getMetadata();
        $isInitialDeposit = isset($metadata['is_initial_deposit']) && $metadata['is_initial_deposit'] === true;

        if (
            $recurringDeposit &&
            $isInitialDeposit &&
            $recurringDeposit->status !== Statuses::ACTIVE->value
        ) {
            $this->recurringDebitStatusUpdateService->execute(
                recurringDeposit: $recurringDeposit,
                status: Statuses::FAILED->value
            );
        }
    }
}
