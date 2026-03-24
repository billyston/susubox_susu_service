<?php

declare(strict_types=1);

namespace App\Application\Transaction\Actions;

use App\Domain\Account\Services\Account\AccountStatusUpdateService;
use App\Domain\PaymentInstruction\Services\PaymentInstruction\PaymentInstructionStatusUpdateService;
use App\Domain\PaymentInstruction\Services\RecurringDeposit\RecurringDepositStatusUpdateService;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Transaction\Models\Transaction;
use Throwable;

final readonly class TransactionCreatedSuccessAction
{
    /**
     * @param AccountStatusUpdateService $accountStatusUpdateService
     * @param RecurringDepositStatusUpdateService $recurringDebitStatusUpdateService
     * @param PaymentInstructionStatusUpdateService $paymentInstructionStatusUpdateService
     */
    public function __construct(
        private AccountStatusUpdateService $accountStatusUpdateService,
        private RecurringDepositStatusUpdateService $recurringDebitStatusUpdateService,
        private PaymentInstructionStatusUpdateService $paymentInstructionStatusUpdateService,
    ) {
        // ..
    }

    /**
     * @throws SystemFailureException
     * @throws Throwable
     */
    public function execute(
        Transaction $transaction,
    ): void {
        // Handle initial deposit statuses updates
        $this->accountStatusUpdateHandler(transaction: $transaction);
        $this->paymentInstructionStatusUpdate(transaction: $transaction);
        $this->recurringDebitStatusUpdateHandler(transaction: $transaction);

        // Other post-transaction actions go here
    }

    /**
     * @throws SystemFailureException
     */
    private function accountStatusUpdateHandler(
        Transaction $transaction
    ): void {
        // Extract the necessary data from $transaction
        $account = $transaction->account;

        // Execute the AccountStatusUpdateService (if 'status' not 'active')
        if ($account->status === Statuses::PENDING->value) {
            $this->accountStatusUpdateService->execute(
                account: $account,
                status: Statuses::ACTIVE->value
            );
        }
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
                status: Statuses::SUCCESS->value,
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
                status: Statuses::ACTIVE->value
            );
        }
    }
}
