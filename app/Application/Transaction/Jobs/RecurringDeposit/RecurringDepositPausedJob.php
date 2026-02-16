<?php

declare(strict_types=1);

namespace App\Application\Transaction\Jobs\RecurringDeposit;

use App\Application\Account\DTOs\AccountPause\AccountPauseResponseDTO;
use App\Application\Transaction\DTOs\RecurringDeposit\RecurringDepositRequestDTO;
use App\Domain\Account\Services\AccountPause\AccountPauseByResourceIdService;
use App\Domain\Account\Services\AccountPause\AccountPauseStatusUpdateService;
use App\Domain\PaymentInstruction\Services\PaymentInstructionStatusUpdateService;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class RecurringDepositPausedJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @param RecurringDepositRequestDTO $requestDTO
     * @param string $accountPauseResourceID
     */
    public function __construct(
        private readonly string $accountPauseResourceID,
        private readonly RecurringDepositRequestDTO $requestDTO,
    ) {
        // ...
    }

    /**
     * @param AccountPauseByResourceIdService $accountPauseByResourceId
     * @param AccountPauseStatusUpdateService $accountPauseStatusUpdateService
     * @param PaymentInstructionStatusUpdateService $paymentInstructionStatusUpdateService
     * @return void
     * @throws SystemFailureException
     */
    public function handle(
        AccountPauseByResourceIdService $accountPauseByResourceId,
        AccountPauseStatusUpdateService $accountPauseStatusUpdateService,
        PaymentInstructionStatusUpdateService $paymentInstructionStatusUpdateService,
    ): void {
        // Execute the AccountPauseByResourceIdService
        $accountPause = $accountPauseByResourceId->execute(
            $this->accountPauseResourceID
        );

        // Build the status from the DTO
        $status = $this->requestDTO->status ? Statuses::ACTIVE->value : Statuses::FAILED->value;

        // Execute the AccountPauseStatusUpdateService
        $accountPauseStatusUpdateService->execute(
            accountPause: $accountPause,
            status: $status
        );

        // Execute the PaymentInstructionStatusUpdateService (if status is true)
        if ($this->requestDTO->status === true) {
            $paymentInstructionStatusUpdateService->execute(
                paymentInstruction: $accountPause->payment,
                status: Statuses::PAUSED->value
            );
        }

        // Build the AccountPauseResponseDTO
        $responseDTO = AccountPauseResponseDTO::fromDomain(
            accountPause: $accountPause,
            action: ['action' => Statuses::PAUSED->value, 'status' => $this->requestDTO->status]
        );

        // Dispatch the RecurringDepositNotificationJob
        RecurringDepositNotificationJob::dispatch(
            responseDTO: $responseDTO,
        );
    }
}
