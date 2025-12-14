<?php

declare(strict_types=1);

namespace App\Application\Transaction\Jobs;

use App\Application\Transaction\DTOs\TransactionCreateResponseDTO;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Transaction\Services\TransactionByResourceIdService;
use App\Services\SusuBox\Http\Requests\TransactionCreatedRequestHandler;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

final class TransactionNotificationJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly string $transactionResource,
        public readonly bool $isInitialDeposit,
    ) {
        // ...
    }

    /**
     * @throws Throwable
     * @throws SystemFailureException
     */
    public function handle(
        TransactionByResourceIdService $transactionByResourceIdService,
        TransactionCreatedRequestHandler $dispatcher,
    ): void {
        // Execute the TransactionByResourceIdService and return the resource
        $transaction = $transactionByResourceIdService->execute(
            resource_id: $this->transactionResource,
        );

        // Build the TransactionCreateResponseDTO
        $responseDto = TransactionCreateResponseDTO::fromDomain(
            transaction: $transaction,
            is_initial_deposit: $this->isInitialDeposit
        );

        // Dispatch the TransactionCreateResponseDTO to SusuBox services
        $dispatcher->sendToSusuBoxService(
            service: config('susubox.notification.name'),
            data: $responseDto->toArray(),
        );
    }
}
