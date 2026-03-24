<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\DailySusu\RecurringDepositPause;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\PaymentInstruction\Models\RecurringDepositPause;
use App\Domain\PaymentInstruction\Services\RecurringDeposit\RecurringDepositPauseStatusUpdateService;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuRecurringDepositPauseCancelAction
{
    private RecurringDepositPauseStatusUpdateService $recurringDepositPauseStatusUpdateService;

    /**
     * @param RecurringDepositPauseStatusUpdateService $recurringDepositPauseStatusUpdateService
     */
    public function __construct(
        RecurringDepositPauseStatusUpdateService $recurringDepositPauseStatusUpdateService
    ) {
        $this->recurringDepositPauseStatusUpdateService = $recurringDepositPauseStatusUpdateService;
    }

    /**
     * @param RecurringDepositPause $recurringDepositPause
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function execute(
        RecurringDepositPause $recurringDepositPause,
    ): JsonResponse {
        // Execute the PaymentInstructionCancelService and return the resource
        $this->recurringDepositPauseStatusUpdateService->execute(
            recurringDepositPause: $recurringDepositPause,
            status: Statuses::CANCELLED->value
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'The account pause process has been canceled successfully.',
        );
    }
}
