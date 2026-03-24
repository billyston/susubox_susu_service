<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\DailySusu\RecurringDepositPause;

use App\Application\PaymentInstruction\DTOs\RecurringDeposit\RecurringDepositPausedResponseDTO;
use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\PaymentInstruction\Models\RecurringDepositPause;
use App\Domain\PaymentInstruction\Services\RecurringDeposit\RecurringDepositPauseStatusUpdateService;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Interface\Resources\V1\Account\AccountPauseResource;
use App\Services\SusuBox\Http\SusuBoxServiceDispatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuRecurringDepositPauseApprovalAction
{
    private RecurringDepositPauseStatusUpdateService $recurringDepositPauseStatusUpdateService;
    private SusuBoxServiceDispatcher $susuboxServiceDispatcher;

    /**
     * @param RecurringDepositPauseStatusUpdateService $recurringDepositPauseStatusUpdateService
     * @param SusuBoxServiceDispatcher $susuboxServiceDispatcher
     */
    public function __construct(
        RecurringDepositPauseStatusUpdateService $recurringDepositPauseStatusUpdateService,
        SusuBoxServiceDispatcher $susuboxServiceDispatcher
    ) {
        $this->recurringDepositPauseStatusUpdateService = $recurringDepositPauseStatusUpdateService;
        $this->susuboxServiceDispatcher = $susuboxServiceDispatcher;
    }

    /**
     * @param RecurringDepositPause $recurringDepositPause
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function execute(
        RecurringDepositPause $recurringDepositPause,
    ): JsonResponse {
        // Build the RecurringDepositResponseDTO
        $responseDTO = RecurringDepositPausedResponseDTO::fromDomain(
            recurringDepositPause: $recurringDepositPause,
        );

        // Dispatch to SusuBox Service (Payment Service)
        $this->susuboxServiceDispatcher->send(
            service: config('susubox.payment.name'),
            endpoint: 'recurring-debits/'.$recurringDepositPause->recurringDeposit->resource_id.'/pause',
            payload: $responseDTO->toArray(),
        );

        // Execute the RecurringDepositPauseStatusUpdateService
        $this->recurringDepositPauseStatusUpdateService->execute(
            recurringDepositPause: $recurringDepositPause,
            status: Statuses::APPROVED->value
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'Your request is successful. You will be notified shortly.',
            data: new AccountPauseResource(
                resource: $recurringDepositPause->refresh()
            )
        );
    }
}
