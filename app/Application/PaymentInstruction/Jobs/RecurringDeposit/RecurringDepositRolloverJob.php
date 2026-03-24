<?php

declare(strict_types=1);

namespace App\Application\PaymentInstruction\Jobs\RecurringDeposit;

use App\Application\PaymentInstruction\DTOs\RecurringDeposit\RecurringDepositRequestDTO;
use App\Application\PaymentInstruction\DTOs\RecurringDeposit\RecurringDepositResponseDTO;
use App\Domain\PaymentInstruction\Services\RecurringDeposit\RecurringDepositByResourceIdService;
use App\Domain\PaymentInstruction\Services\RecurringDeposit\RecurringDepositRolloverEnabledService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Services\SusuBox\Http\SusuBoxServiceDispatcher;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class RecurringDepositRolloverJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @param string $recurringDepositResourceID
     * @param RecurringDepositRequestDTO $requestDTO
     */
    public function __construct(
        private readonly string $recurringDepositResourceID,
        private readonly RecurringDepositRequestDTO $requestDTO,
    ) {
        // ...
    }

    /**
     * @param RecurringDepositByResourceIdService $recurringDepositByResourceIdService
     * @param RecurringDepositRolloverEnabledService $recurringDepositRolloverEnabledService
     * @param SusuBoxServiceDispatcher $susuBoxServiceDispatcher
     * @return void
     * @throws SystemFailureException
     */
    public function handle(
        RecurringDepositByResourceIdService $recurringDepositByResourceIdService,
        RecurringDepositRolloverEnabledService $recurringDepositRolloverEnabledService,
        SusuBoxServiceDispatcher $susuBoxServiceDispatcher,
    ): void {
        // Execute the RecurringDepositByResourceIdService
        $recurringDeposit = $recurringDepositByResourceIdService->execute(
            recurringDepositResourceID: $this->recurringDepositResourceID
        );

        // (Guard): Execute the
        if ($recurringDeposit->rollover_enabled === $this->requestDTO->rolloverEnabled) {
            // Build the RecurringDepositResponseDTO
            RecurringDepositResponseDTO::fromDomain(
                recurringDeposit: $recurringDeposit,
                isSuccessful: false
            );

            // Exit the execution
            return;
        }

        // Execute the RecurringDepositRolloverEnabledService
        $recurringDepositRolloverEnabledService->execute(
            recurringDeposit: $recurringDeposit,
            state: $this->requestDTO->rolloverEnabled
        );

        // Build the RecurringDepositResponseDTO
        $responseDTO = RecurringDepositResponseDTO::fromDomain(
            recurringDeposit: $recurringDeposit,
            isSuccessful: true
        );

        // Dispatch the SusuBoxServiceDispatcher to SusuBox services
        $susuBoxServiceDispatcher->send(
            service: config('susubox.notification.name'),
            endpoint: 'account/susu/rollover',
            payload: $responseDTO->toArray(),
        );
    }
}
