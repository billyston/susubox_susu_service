<?php

declare(strict_types=1);

namespace App\Application\Susu\Jobs\IndividualSusu\DailySusu;

use App\Application\Susu\DTOs\DailySusu\AccountCreate\DailySusuCycleResponseDTO;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Services\DailySusu\DailySusuCycleCreateService;
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

final class DailySusuCycleCreateJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly string $resourceID,
    ) {
        // ...
    }

    /**
     * @throws SystemFailureException
     * @throws Throwable
     */
    public function handle(
        TransactionByResourceIdService $transactionByResourceIdService,
        DailySusuCycleCreateService $dailySusuCycleCreateService
    ): void {
        // Execute the TransactionByResourceIdService and return the resource
        $transaction = $transactionByResourceIdService->execute(
            resourceID: $this->resourceID,
        );

        // Resolve and handle the DailySusuCycleCreateService
        match (true) {
            $transaction->status !== Statuses::SUCCESS->value => null,
            $transaction->transaction_type !== TransactionType::CREDIT->value => null,

            // Handle the dailySusuCycleCreate
            default => $this->dailySusuCycleCreate(
                transaction: $transaction,
                dailySusuCycleCreateService: $dailySusuCycleCreateService
            ),
        };
    }

    /**
     * @throws Throwable
     */
    private function dailySusuCycleCreate(
        Transaction $transaction,
        DailySusuCycleCreateService $dailySusuCycleCreateService
    ): void {
        // Get the DailySusu
        $dailySusu = $transaction->account->accountable->susu();

        // Build the DailySusuCycleResponseDTO
        $responseDTO = DailySusuCycleResponseDTO::fromDomain(
            dailySusu: $dailySusu,
            transaction: $transaction,
        );

        // Execute the dailySusuCycleService
        $dailySusuCycleCreateService::execute(
            responseDTO: $responseDTO,
        );
    }
}
