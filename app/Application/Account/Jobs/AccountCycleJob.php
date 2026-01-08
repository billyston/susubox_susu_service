<?php

declare(strict_types=1);

namespace App\Application\Account\Jobs;

use App\Application\Susu\DTOs\DailySusu\DailySusuCycleResponseDTO;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Domain\Susu\Services\DailySusu\DailySusuCycleService;
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

final class AccountCycleJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly string $transactionResource,
    ) {
        // ...
    }

    /**
     * @throws SystemFailureException
     * @throws Throwable
     */
    public function handle(
        TransactionByResourceIdService $transactionByResourceIdService,
        DailySusuCycleService $dailySusuCycleService,
    ): void {
        // Execute the TransactionByResourceIdService and return the resource
        $transaction = $transactionByResourceIdService->execute(
            resourceID: $this->transactionResource,
        );

        // Get the susu (DailySusu, etc)
        $cycleable = $transaction->account->accountable->susu();

        // Build the DTO
        $responseDTO = $this->buildCycleDTO(
            cycleable: $cycleable,
            transaction: $transaction,
        );

        /**
         * Guard using match
         */
        match (true) {
            $transaction->status !== Statuses::SUCCESS->value => null,
            $transaction->transaction_type !== TransactionType::CREDIT->value => null,
            ! $cycleable => null,

            default => match (true) {
                $cycleable instanceof DailySusu => $dailySusuCycleService->execute(
                    responseDTO: $responseDTO
                ),

                default => null,
            },
        };
    }

    /**
     * @param mixed $cycleable
     * @param Transaction $transaction
     * @return DailySusuCycleResponseDTO|null
     */
    private function buildCycleDTO(
        mixed $cycleable,
        Transaction $transaction,
    ): DailySusuCycleResponseDTO|null {
        return match (true) {
            $cycleable instanceof DailySusu => DailySusuCycleResponseDTO::fromDomain(
                dailySusu: $cycleable,
                transaction: $transaction,
            ),

            default => null,
        };
    }
}
