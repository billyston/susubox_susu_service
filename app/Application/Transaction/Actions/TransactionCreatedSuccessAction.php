<?php

declare(strict_types=1);

namespace App\Application\Transaction\Actions;

use App\Domain\Account\Services\Account\AccountStatusUpdateService;
use App\Domain\PaymentInstruction\Services\PaymentInstructionStatusUpdateService;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Transaction\Enums\TransactionCategoryCode;
use App\Domain\Transaction\Models\Transaction;
use App\Domain\Transaction\Services\RecurringDebitStatusUpdateService;
use Throwable;

final readonly class TransactionCreatedSuccessAction
{
    public function __construct(
        private AccountStatusUpdateService $accountStatusUpdateService,
        private RecurringDebitStatusUpdateService $recurringDebitStatusUpdateService,
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
        // Update the Account and Susu (If transaction isInitialDeposit)
        match ($this->isInitialDeposit($transaction)) {
            true => $this->activateAccountAndSusu($transaction),
            false => null,
        };

        // Execute the PaymentInstructionStatus
        $this->updatePaymentInstructionStatus($transaction);

        // Other post-transaction actions go here
    }

    /**
     * @param Transaction $transaction
     * @return bool
     */
    private function isInitialDeposit(
        Transaction $transaction
    ): bool {
        return (bool) data_get(
            target: $transaction->extra_data,
            key: 'is_initial_deposit',
            default: false
        );
    }

    /**
     * @throws SystemFailureException
     */
    private function activateAccountAndSusu(
        Transaction $transaction
    ): void {
        $account = $transaction->account;
        $susu = $account->accountable->susu();

        // Update the Account status
        match ($account->status) {
            Statuses::ACTIVE->value => null,
            default => $this->accountStatusUpdateService->execute(
                account: $account,
                status: Statuses::ACTIVE->value
            ),
        };

        // Update the Susu (recurring_debit_status)
        match ($susu->status) {
            Statuses::ACTIVE->value => null,
            default => $this->recurringDebitStatusUpdateService->execute(
                model: $susu,
                status: Statuses::ACTIVE->value
            ),
        };
    }

    /**
     * @throws SystemFailureException
     */
    private function updatePaymentInstructionStatus(
        Transaction $transaction
    ): void {
        // Get the PaymentInstruction from Transaction
        $payment = $transaction->payment;

        // Update PaymentInstruction status
        $targetStatus = match ($payment->transactionCategory->code) {
            // Set status to (active) if transaction is recurring_debit
            TransactionCategoryCode::RECURRING_DEBIT_CODE->value => Statuses::ACTIVE->value,

            // Set others to success
            default => Statuses::SUCCESS->value,
        };

        // Update the PaymentInstruction status
        match ($payment->status === $targetStatus) {
            true => null,
            false => $this->paymentInstructionStatusUpdateService->execute(
                paymentInstruction: $payment,
                status: $targetStatus
            ),
        };
    }
}
