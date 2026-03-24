<?php

declare(strict_types=1);

namespace App\Application\PaymentInstruction\Jobs\RecurringDeposit;

use App\Application\PaymentInstruction\DTOs\RecurringDeposit\RecurringDepositPausedRequestDTO;
use App\Application\PaymentInstruction\DTOs\RecurringDeposit\RecurringDepositPausedResponseDTO;
use App\Domain\PaymentInstruction\Services\RecurringDeposit\RecurringDepositPauseByResourceIdService;
use App\Domain\PaymentInstruction\Services\RecurringDeposit\RecurringDepositPauseStatusUpdateService;
use App\Domain\PaymentInstruction\Services\RecurringDeposit\RecurringDepositStatusUpdateService;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Services\SusuBox\Http\SusuBoxServiceDispatcher;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class RecurringDepositResumedJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @param string $recurringDepositPauseResourceID
     * @param RecurringDepositPausedRequestDTO $requestDTO
     */
    public function __construct(
        private readonly string $recurringDepositPauseResourceID,
        private readonly RecurringDepositPausedRequestDTO $requestDTO,
    ) {
        // ...
    }

    /**
     * @param RecurringDepositPauseByResourceIdService $recurringDepositPauseByResourceIDService
     * @param RecurringDepositPauseStatusUpdateService $recurringDepositPauseStatusUpdateService
     * @param RecurringDepositStatusUpdateService $recurringDepositStatusUpdateService
     * @param SusuBoxServiceDispatcher $susuBoxServiceDispatcher
     * @return void
     * @throws SystemFailureException
     */
    public function handle(
        RecurringDepositPauseByResourceIdService $recurringDepositPauseByResourceIDService,
        RecurringDepositPauseStatusUpdateService $recurringDepositPauseStatusUpdateService,
        RecurringDepositStatusUpdateService $recurringDepositStatusUpdateService,
        SusuBoxServiceDispatcher $susuBoxServiceDispatcher,
    ): void {
        // Execute the RecurringDepositByResourceIdService and return the resource
        $recurringDepositPause = $recurringDepositPauseByResourceIDService->execute(
            recurringDepositPauseResourceID: $this->recurringDepositPauseResourceID
        );

        // Execute the PaymentInstructionStatusUpdateService (if status is paused)
        if ($this->requestDTO->status !== Statuses::ACTIVE->value) {
            // Execute the RecurringDepositPauseDeleteService

            // Exit the execution
            return;
        }

        // Execute the RecurringDepositPauseStatusUpdateService
        $recurringDepositPauseStatusUpdateService->execute(
            recurringDepositPause: $recurringDepositPause,
            status: Statuses::EXPIRED->value
        );

        // Execute the RecurringDepositPauseStatusUpdateService (if status is true)
        $recurringDepositStatusUpdateService->execute(
            $recurringDepositPause->recurringDeposit,
            status: Statuses::ACTIVE->value
        );

        // Build the RecurringDepositPausedResponseDTO
        $responseDTO = RecurringDepositPausedResponseDTO::fromDomain(
            recurringDepositPause: $recurringDepositPause,
        );

        // Dispatch the SusuBoxServiceDispatcher to SusuBox services
        $susuBoxServiceDispatcher->send(
            service: config('susubox.notification.name'),
            endpoint: 'account/susu/pause',
            payload: $responseDTO->toArray(),
        );
    }
}
