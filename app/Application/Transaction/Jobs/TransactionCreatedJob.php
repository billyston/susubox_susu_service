<?php

declare(strict_types=1);

namespace App\Application\Transaction\Jobs;

use App\Application\Transaction\Actions\TransactionCreatedFailureAction;
use App\Application\Transaction\Actions\TransactionCreatedSuccessAction;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Transaction\Enums\TransactionStatus;
use App\Domain\Transaction\Models\Transaction;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
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
    ) {
        // ...
    }

    /**
     * @throws SystemFailureException
     */
    public function handle(
    ): void {
        try {
            // Define the StatusActions array
            $statusActions = [
                TransactionStatus::SUCCESS->value => app(TransactionCreatedSuccessAction::class),
                TransactionStatus::FAILED->value => app(TransactionCreatedFailureAction::class),
            ];

            // Execute the (TransactionCreatedSuccessAction, TransactionCreatedFailureAction)
            $statusActions[$this->transaction->status->value]->execute($this->transaction);
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in TransactionCreatedJob', [
                'transaction' => $this->transaction,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'An error occurred while trying to dispatch the transaction created job.',
            );
        }
    }
}
