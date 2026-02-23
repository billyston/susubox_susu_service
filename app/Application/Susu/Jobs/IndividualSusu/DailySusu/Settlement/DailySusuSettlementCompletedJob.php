<?php

declare(strict_types=1);

namespace App\Application\Susu\Jobs\IndividualSusu\DailySusu\Settlement;

use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Services\IndividualSusu\DailySusu\Settlement\DailySusuSettlementCompletedService;
use App\Domain\Transaction\Enums\TransactionType;
use App\Domain\Transaction\Models\Transaction;
use App\Domain\Transaction\Services\TransactionByResourceIdService;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

final class DailySusuSettlementCompletedJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @param string $transactionResourceID
     */
    public function __construct(
        private readonly string $transactionResourceID
    ) {
        // ...
    }

    /**
     * @throws SystemFailureException
     * @throws Throwable
     */
    public function handle(
        TransactionByResourceIdService $transactionByResourceIdService,
        DailySusuSettlementCompletedService $dailySusuSettlementCompletedService
    ): void {
        // Execute the TransactionByResourceIdService and return the resource
        $transaction = $transactionByResourceIdService->execute(
            resourceID: $this->transactionResourceID,
        );

        // Resolve and execute the DailySusuCycleCreateService
        match (true) {
            // Handle the guards
            $transaction->status !== Statuses::SUCCESS->value => null,
            $transaction->transaction_type !== TransactionType::DEBIT->value => null,

            // Handle the dailySusuSettlementCompleted
            default => $this->dailySusuSettlementCompleted(
                transaction: $transaction,
                dailySusuSettlementCompletedService: $dailySusuSettlementCompletedService,
            ),
        };
    }

    /**
     * @throws SystemFailureException
     */
    private function dailySusuSettlementCompleted(
        Transaction $transaction,
        DailySusuSettlementCompletedService $dailySusuSettlementCompletedService
    ): void {
        // Get the Settlement
        $settlement = $transaction->payment->settlement;

        // Terminate the process (if $settlement is already completed)
        if ($settlement->status === Statuses::COMPLETED->value) {
            return;
        }

        // Execute the DailySusuSettlementCompletedService
        $dailySusuSettlementCompletedService->execute(
            accountSettlement: $settlement,
        );
    }
}
