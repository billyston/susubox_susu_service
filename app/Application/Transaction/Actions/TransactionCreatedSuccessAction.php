<?php

declare(strict_types=1);

namespace App\Application\Transaction\Actions;

use App\Domain\Account\Enums\AccountStatus;
use App\Domain\Account\Services\AccountStatusUpdateService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Services\SchemeAccountStatusUpdateService;
use App\Domain\Transaction\Models\Transaction;
use App\Services\Shared\Jobs\Transaction\TransactionCreatedPublishJob;

final class TransactionCreatedSuccessAction
{
    private AccountStatusUpdateService $accountStatusUpdateService;
    private SchemeAccountStatusUpdateService $schemeAccountStatusUpdateService;

    public function __construct(
        AccountStatusUpdateService $accountStatusUpdateService,
        SchemeAccountStatusUpdateService $schemeAccountStatusUpdateService
    ) {
        $this->accountStatusUpdateService = $accountStatusUpdateService;
        $this->schemeAccountStatusUpdateService = $schemeAccountStatusUpdateService;
    }

    /**
     * @throws SystemFailureException
     */
    public function execute(
        Transaction $transaction,
    ): void {
        // Handle initial deposit activation
        if ($transaction->extra_data['is_initial_deposit'] ?? false) {
            // Execute the AccountStatusUpdateService
            $this->accountStatusUpdateService->execute(
                account: $transaction->account,
                status: AccountStatus::ACTIVE->value
            );

            // Execute the SchemeAccountStatusUpdateService
            $this->schemeAccountStatusUpdateService->execute(
                account: $transaction->account,
            );

            // Dispatch the TransactionCreatedPublishJob (asynchronously)
            TransactionCreatedPublishJob::dispatch(
                transaction: $transaction
            );
        }

        // Other Account to be handled here
    }
}
