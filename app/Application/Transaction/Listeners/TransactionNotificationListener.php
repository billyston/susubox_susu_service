<?php

declare(strict_types=1);

namespace App\Application\Transaction\Listeners;

use App\Application\Transaction\DTOs\TransactionCreateResponseDTO;
use App\Application\Transaction\Interfaces\TransactionCreatedEvent;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Transaction\Enums\TransactionCategoryCode;
use App\Domain\Transaction\Services\TransactionByResourceIdService;
use App\Services\SusuBox\Http\SusuBoxServiceDispatcher;
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
    private SusuBoxServiceDispatcher $susuBoxServiceDispatcher;

    /**
     * @param TransactionByResourceIdService $transactionByResourceIdService
     * @param SusuBoxServiceDispatcher $susuBoxServiceDispatcher
     */
    public function __construct(
        TransactionByResourceIdService $transactionByResourceIdService,
        SusuBoxServiceDispatcher $susuBoxServiceDispatcher
    ) {
        $this->transactionByResourceIdService = $transactionByResourceIdService;
        $this->susuBoxServiceDispatcher = $susuBoxServiceDispatcher;
    }

    /**
     * @param TransactionCreatedEvent $transactionCreatedEvent
     * @return void
     * @throws SystemFailureException
     */
    public function handle(
        TransactionCreatedEvent $transactionCreatedEvent
    ): void {
        // Execute the TransactionByResourceIdService and return the resource
        $transaction = $this->transactionByResourceIdService->execute(
            resourceID: $transactionCreatedEvent->transactionResourceId,
        );

        /**
         * Do NOT send notification for
         * recurring_deposit that is NOT initial_deposit
         */
        if (
            $transaction->transactionCategory->code === TransactionCategoryCode::RECURRING_DEBIT_CODE->value &&
            $transaction->metadata['is_initial_deposit'] === false
        ) {
            return;
        }

        // Build the TransactionCreateResponseDTO
        $responseDTO = TransactionCreateResponseDTO::fromDomain(
            transaction: $transaction,
        );

        // Dispatch the SusuBoxServiceDispatcher to SusuBox services
        $this->susuBoxServiceDispatcher->send(
            service: config('susubox.notification.name'),
            endpoint: 'transactions',
            payload: $responseDTO->toArray(),
        );
    }
}
