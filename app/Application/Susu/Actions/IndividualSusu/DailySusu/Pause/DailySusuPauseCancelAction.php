<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\DailySusu\Pause;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Account\Models\AccountPause;
use App\Domain\Account\Services\AccountPause\AccountPauseStatusUpdateService;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuPauseCancelAction
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
     * @param AccountPause $accountPause
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function execute(
        AccountPause $accountPause,
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
            description: 'The account lock process has been canceled successfully.',
        );
    }
}
