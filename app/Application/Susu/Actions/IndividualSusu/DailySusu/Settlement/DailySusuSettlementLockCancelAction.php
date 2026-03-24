<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\DailySusu\Settlement;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Account\Models\AccountPayoutLock;
use App\Domain\Account\Services\AccountPayoutLock\AccountPayoutLockStatusUpdateService;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuSettlementLockCancelAction
{
    private AccountPayoutLockStatusUpdateService $accountPayoutLockStatusUpdateService;

    /**
     * @param AccountPayoutLockStatusUpdateService $accountPayoutLockStatusUpdateService
     */
    public function __construct(
        AccountPayoutLockStatusUpdateService $accountPayoutLockStatusUpdateService
    ) {
        $this->accountPayoutLockStatusUpdateService = $accountPayoutLockStatusUpdateService;
    }

    /**
     * @param AccountPayoutLock $accountPayoutLock
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function execute(
        AccountPayoutLock $accountPayoutLock,
    ): JsonResponse {
        // Execute the AccountPayoutLockStatusUpdateService and return the resource
        $this->accountPayoutLockStatusUpdateService->execute(
            accountPayoutLock: $accountPayoutLock,
            status: Statuses::CANCELLED->value
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'The settlement lock process has been canceled successfully.',
        );
    }
}
