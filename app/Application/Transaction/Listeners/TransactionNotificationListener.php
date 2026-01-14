<?php

declare(strict_types=1);

namespace App\Application\Transaction\Listeners;

use App\Application\Transaction\DTOs\TransactionCreateResponseDTO;
use App\Application\Transaction\Interfaces\TransactionCreatedEvent;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Transaction\Enums\TransactionCategoryCode;
use App\Domain\Transaction\Services\TransactionByResourceIdService;
use App\Services\SusuBox\Http\Requests\Notification\NotificationRequestHandler;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class TransactionNotificationListener implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private TransactionByResourceIdService $transactionByResourceIdService;
    private NotificationRequestHandler $dispatcher;

    /**
     * @param TransactionByResourceIdService $transactionByResourceIdService
     * @param NotificationRequestHandler $dispatcher
     */
    public function __construct(
        TransactionByResourceIdService $transactionByResourceIdService,
        NotificationRequestHandler $dispatcher
    ) {
        $this->transactionByResourceIdService = $transactionByResourceIdService;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param TransactionCreatedEvent $transactionCreatedEvent
     * @return void
     * @throws SystemFailureException
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

        /**
         * Do NOT send notification for
         * recurring_deposit that is NOT initial_deposit
         */
        if (
            $transaction->category->code === TransactionCategoryCode::RECURRING_DEBIT_CODE->value
            && $transaction->extra_data['is_initial_deposit'] === false
        ) {
            return;
        }

        // Build the TransactionCreateResponseDTO
        $responseDTO = TransactionCreateResponseDTO::fromDomain(
            transaction: $transaction,
            isInitialDeposit: $transaction->extra_data['is_initial_deposit']
        );

        // Dispatch the TransactionCreateResponseDTO to SusuBox services
        $this->dispatcher->sendToSusuBoxService(
            service: config('susubox.notification.name'),
            endpoint: 'transactions',
            data: $responseDTO->toArray(),
        );
    }
}
