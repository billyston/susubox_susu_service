<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\GoalGetterSusu\Pause;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\PaymentInstruction\Models\RecurringDepositPause;
use App\Domain\PaymentInstruction\Services\RecurringDeposit\RecurringDepositPauseStatusUpdateService;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Interface\Resources\V1\Account\AccountPauseResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class GoalGetterSusuPauseApprovalAction
{
    private RecurringDepositPauseStatusUpdateService $AccountPauseStatusUpdateService;

    public function __construct(
        RecurringDepositPauseStatusUpdateService $AccountPauseStatusUpdateService,
    ) {
        $this->AccountPauseStatusUpdateService = $AccountPauseStatusUpdateService;
    }

    /**
     * @param RecurringDepositPause $accountPause
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function execute(
        RecurringDepositPause $accountPause,
    ): JsonResponse {
        // Execute the RecurringDepositPauseStatusUpdateService
        $this->AccountPauseStatusUpdateService->execute(
            recurringDepositPause: $accountPause,
            status: Statuses::APPROVED->value
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'Your request is successful. You will be notified shortly.',
            data: new AccountPauseResource(
                resource: $accountPause->refresh()
            )
        );
    }
}
