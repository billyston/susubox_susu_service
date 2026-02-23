<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\BizSusu\Pause;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Account\Services\AccountPause\AccountPauseStatusUpdateService;
use App\Domain\PaymentInstruction\Models\RecurringDepositPause;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class BizSusuPauseCancelAction
{
    private AccountPauseStatusUpdateService $accountPauseStatusUpdateService;

    /**
     * @param AccountPauseStatusUpdateService $accountPauseStatusUpdateService
     */
    public function __construct(
        AccountPauseStatusUpdateService $accountPauseStatusUpdateService
    ) {
        $this->accountPauseStatusUpdateService = $accountPauseStatusUpdateService;
    }

    /**
     * @param RecurringDepositPause $accountPause
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function execute(
        RecurringDepositPause $accountPause,
    ): JsonResponse {
        // Execute the PaymentInstructionCancelService and return the resource
        $this->accountPauseStatusUpdateService->execute(
            accountPause: $accountPause,
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
