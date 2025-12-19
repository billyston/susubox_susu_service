<?php

declare(strict_types=1);

namespace App\Application\Transaction\Actions;

use App\Domain\Account\Services\AccountStatusUpdateService;
use App\Domain\PaymentInstruction\Services\PaymentInstructionStatusUpdateService;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Transaction\Enums\TransactionCategoryCode;
use App\Domain\Transaction\Models\Transaction;
use App\Domain\Transaction\Services\RecurringDebitStatusUpdateService;
use Throwable;

final class TransactionCreatedSuccessAction
{
    private AccountStatusUpdateService $accountStatusUpdateService;
    private RecurringDebitStatusUpdateService $recurringDebitStatusUpdateService;
    private PaymentInstructionStatusUpdateService $paymentInstructionStatusUpdateService;

    /**
     * @param AccountStatusUpdateService $accountStatusUpdateService
     * @param RecurringDebitStatusUpdateService $recurringDebitStatusUpdateService
     * @param PaymentInstructionStatusUpdateService $paymentInstructionStatusUpdateService
     */
    public function __construct(
        AccountStatusUpdateService $accountStatusUpdateService,
        RecurringDebitStatusUpdateService $recurringDebitStatusUpdateService,
        PaymentInstructionStatusUpdateService $paymentInstructionStatusUpdateService,
    ) {
        $this->accountStatusUpdateService = $accountStatusUpdateService;
        $this->recurringDebitStatusUpdateService = $recurringDebitStatusUpdateService;
        $this->paymentInstructionStatusUpdateService = $paymentInstructionStatusUpdateService;
    }

    /**
     * @throws SystemFailureException
     * @throws Throwable
     */
    public function execute(
        Transaction $transaction,
        array $responseDTO,
    ): void {
        // Handle initial deposit activation
        if ($responseDTO['data']['attributes']['is_initial_deposit']) {
            // Execute the AccountStatusUpdateService
            $this->accountStatusUpdateService->execute(
                account: $transaction->account,
                status: Statuses::ACTIVE->value
            );

            // Execute the RecurringDebitStatusUpdateService
            $this->recurringDebitStatusUpdateService->execute(
                model: $transaction->account->accountable->susu(),
                status: Statuses::ACTIVE->value
            );
        }

        // Update payment instruction
        if ($transaction->payment->transactionCategory->code === TransactionCategoryCode::RECURRING_DEBIT_CODE->value) {
            if ($transaction->payment->status !== Statuses::ACTIVE->value) {
                $this->paymentInstructionStatusUpdateService->execute(
                    paymentInstruction: $transaction->payment,
                    status: Statuses::ACTIVE->value
                );
            }
        } else {
            $this->paymentInstructionStatusUpdateService->execute(
                paymentInstruction: $transaction->payment,
                status: Statuses::SUCCESS->value
            );
        }

        // Other actions goes here
    }
}
