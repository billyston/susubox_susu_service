<?php

declare(strict_types=1);

namespace App\Application\Transaction\Jobs;

use App\Application\Transaction\Actions\TransactionCreatedFailureAction;
use App\Application\Transaction\Actions\TransactionCreatedSuccessAction;
use App\Application\Transaction\DTOs\TransactionCreateResponseDTO;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Transaction\Models\Transaction;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

final class TransactionCreatedJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly Transaction $transaction,
        public readonly bool $isInitialDeposit,
    ) {
        // ...
    }

    /**
     * @throws Throwable
     * @throws SystemFailureException
     */
    public function handle(
    ): void {
        // Build the TransactionCreateResponseDTO
        $response_dto = TransactionCreateResponseDTO::fromDomain(
            transaction: $this->transaction,
            isInitialDeposit: $this->isInitialDeposit
        );

        // Check the transaction status and execute the appropriate action
        $action = $this->resolveAction($this->transaction->status);
        $action->execute(
            transaction: $this->transaction,
            responseDto: $response_dto->toArray()
        );
    }

    private function resolveAction(
        string $status
    ): object {
        return match ($status) {
            Statuses::SUCCESS->value => app(TransactionCreatedSuccessAction::class),
            Statuses::FAILED->value => app(TransactionCreatedFailureAction::class),
        };
    }
}
