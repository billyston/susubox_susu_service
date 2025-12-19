<?php

declare(strict_types=1);

namespace App\Application\Transaction\Jobs;

use App\Application\Transaction\Actions\TransactionCreatedFailureAction;
use App\Application\Transaction\Actions\TransactionCreatedSuccessAction;
use App\Application\Transaction\DTOs\TransactionCreateResponseDTO;
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

final class TransactionPostProcessJob implements ShouldQueue
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
     * @param TransactionCreatedSuccessAction $transactionCreatedSuccessAction
     * @param TransactionCreatedFailureAction $transactionCreatedFailureAction
     * @return void
     * @throws SystemFailureException
     * @throws Throwable
     */
    public function handle(
        TransactionByResourceIdService $transactionByResourceIdService,
        TransactionCreatedSuccessAction $transactionCreatedSuccessAction,
        TransactionCreatedFailureAction $transactionCreatedFailureAction,
    ): void {
        // Execute the TransactionByResourceIdService and return the resource
        $transaction = $transactionByResourceIdService->execute(
            resourceID: $this->transactionResource,
        );

        // Build the TransactionCreateResponseDTO
        $responseDTO = TransactionCreateResponseDTO::fromDomain(
            transaction: $transaction,
            isInitialDeposit: $this->isInitialDeposit
        );

        // Evaluate and execute the (SuccessAction / FailureAction)
        match ($transaction->status) {
            Statuses::SUCCESS->value => $transactionCreatedSuccessAction->execute(
                transaction: $transaction,
                responseDTO: $responseDTO->toArray()
            ),
            Statuses::FAILED->value => $transactionCreatedFailureAction->execute(
                transaction: $transaction,
                responseDTO: $responseDTO->toArray()
            ),
        };
    }
}
