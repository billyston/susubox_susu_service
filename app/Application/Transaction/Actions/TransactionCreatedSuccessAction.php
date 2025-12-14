<?php

declare(strict_types=1);

namespace App\Application\Transaction\Actions;

use App\Domain\Account\Services\AccountStatusUpdateService;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Services\RecurringDebitStatusUpdateService;
use App\Domain\Transaction\Models\Transaction;
use Throwable;

final class TransactionCreatedSuccessAction
{
    private AccountStatusUpdateService $accountStatusUpdateService;
    private RecurringDebitStatusUpdateService $recurringDebitStatusUpdateService;

    public function __construct(
        AccountStatusUpdateService $accountStatusUpdateService,
        RecurringDebitStatusUpdateService $recurringDebitStatusUpdateService,
    ) {
        $this->accountStatusUpdateService = $accountStatusUpdateService;
        $this->recurringDebitStatusUpdateService = $recurringDebitStatusUpdateService;
    }

    /**
     * @throws SystemFailureException
     * @throws Throwable
     */
    public function execute(
        Transaction $transaction,
        array $responseDto,
    ): void {
        // Handle initial deposit activation
        if ($responseDto['data']['attributes']['is_initial_deposit']) {
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

        // Other actions goes here
    }
}
