<?php

declare(strict_types=1);

namespace App\Application\Transaction\Listeners;

use App\Application\Transaction\Actions\TransactionCreatedFailureAction;
use App\Application\Transaction\Actions\TransactionCreatedSuccessAction;
use App\Application\Transaction\Interfaces\TransactionCreatedEvent;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Transaction\Services\TransactionByResourceIdService;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

final class TransactionAccountStatusUpdateListener implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private TransactionByResourceIdService $transactionByResourceIdService;
    private TransactionCreatedSuccessAction $transactionCreatedSuccessAction;
    private TransactionCreatedFailureAction $transactionCreatedFailureAction;

    /**
     * @param TransactionByResourceIdService $transactionByResourceIdService
     * @param TransactionCreatedSuccessAction $transactionCreatedSuccessAction
     * @param TransactionCreatedFailureAction $transactionCreatedFailureAction
     */
    public function __construct(
        TransactionByResourceIdService $transactionByResourceIdService,
        TransactionCreatedSuccessAction $transactionCreatedSuccessAction,
        TransactionCreatedFailureAction $transactionCreatedFailureAction
    ) {
        $this->transactionByResourceIdService = $transactionByResourceIdService;
        $this->transactionCreatedSuccessAction = $transactionCreatedSuccessAction;
        $this->transactionCreatedFailureAction = $transactionCreatedFailureAction;
    }

    /**
     * @param TransactionCreatedEvent $transactionCreatedEvent
     * @return void
     * @throws SystemFailureException
     * @throws Throwable
     */
    public function handle(
        TransactionCreatedEvent $transactionCreatedEvent
    ): void {
        // Get the transactionResourceID from the TransactionCreditCreatedEvent
        $transactionResourceID = $transactionCreatedEvent->transactionResourceId;

        // Execute the TransactionByResourceIdService and return the resource
        $transaction = $this->transactionByResourceIdService->execute(
            resourceID: $transactionResourceID,
        );

        // Evaluate and execute the (SuccessAction / FailureAction)
        match ($transaction->status) {
            Statuses::SUCCESS->value => $this->transactionCreatedSuccessAction->execute(
                transaction: $transaction,
            ),
            Statuses::FAILED->value => $this->transactionCreatedFailureAction->execute(
                transaction: $transaction,
            ),
        };
    }
}
