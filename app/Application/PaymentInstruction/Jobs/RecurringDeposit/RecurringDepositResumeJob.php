<?php

declare(strict_types=1);

namespace App\Application\PaymentInstruction\Jobs\RecurringDeposit;

use App\Application\PaymentInstruction\DTOs\RecurringDeposit\RecurringDepositPausedResponseDTO;
use App\Domain\PaymentInstruction\Services\RecurringDeposit\RecurringDepositPauseByResourceIdService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Services\SusuBox\Http\SusuBoxServiceDispatcher;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class RecurringDepositResumeJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @param string $recurringDepositPauseResourceID
     */
    public function __construct(
        private readonly string $recurringDepositPauseResourceID
    ) {
        // ...
    }

    /**
     * @param RecurringDepositPauseByResourceIdService $recurringDepositPauseByResourceIdService
     * @param SusuBoxServiceDispatcher $susuBoxServiceDispatcher
     * @return void
     * @throws SystemFailureException
     */
    public function handle(
        RecurringDepositPauseByResourceIdService $recurringDepositPauseByResourceIdService,
        SusuBoxServiceDispatcher $susuBoxServiceDispatcher,
    ): void {
        // Execute the AccountPauseByResourceIdService and return the resource
        $recurringDepositPause = $recurringDepositPauseByResourceIdService->execute(
            recurringDepositPauseResourceID: $this->recurringDepositPauseResourceID
        );

        // Build the RecurringDepositPausedResponseDTO
        $responseDTO = RecurringDepositPausedResponseDTO::fromDomain(
            recurringDepositPause: $recurringDepositPause,
        );

        // Dispatch to SusuBox Service (Payment Service)
        $susuBoxServiceDispatcher->send(
            service: config('susubox.payment.name'),
            endpoint: 'recurring-debits/'.$recurringDepositPause->recurringDeposit->resource_id.'/resume',
            payload: $responseDTO->toArray(),
        );
    }
}
