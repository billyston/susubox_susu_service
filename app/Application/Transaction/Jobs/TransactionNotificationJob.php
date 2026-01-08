<?php

declare(strict_types=1);

namespace App\Application\Transaction\Jobs;

use App\Application\Transaction\DTOs\TransactionCreateResponseDTO;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Transaction\Enums\TransactionCategoryCode;
use App\Domain\Transaction\Services\TransactionByResourceIdService;
use App\Services\SusuBox\Http\Requests\TransactionCreatedRequestHandler;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class TransactionNotificationJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @param string $transactionResource
     * @param bool $isInitialDeposit
     */
    public function __construct(
        public readonly string $transactionResource,
        public readonly bool $isInitialDeposit,
    ) {
        // ...
    }

    /**
     * @param TransactionByResourceIdService $transactionByResourceIdService
     * @param TransactionCreatedRequestHandler $dispatcher
     * @return void
     * @throws SystemFailureException
     */
    public function handle(
        TransactionByResourceIdService $transactionByResourceIdService,
        TransactionCreatedRequestHandler $dispatcher,
    ): void {
        // Execute the TransactionByResourceIdService and return the resource
        $transaction = $transactionByResourceIdService->execute(
            resourceID: $this->transactionResource,
        );

        /**
         * Do NOT send notification for
         * recurring_deposit that is NOT initial_deposit
         */
        if (
            $transaction->category->code === TransactionCategoryCode::RECURRING_DEBIT_CODE->value
            && $this->isInitialDeposit === false
        ) {
            return;
        }

        // Build the TransactionCreateResponseDTO
        $responseDTO = TransactionCreateResponseDTO::fromDomain(
            transaction: $transaction,
            isInitialDeposit: $this->isInitialDeposit
        );

        // Dispatch the TransactionCreateResponseDTO to SusuBox services
        $dispatcher->sendToSusuBoxService(
            service: config('susubox.notification.name'),
            data: $responseDTO->toArray(),
        );
    }
}
