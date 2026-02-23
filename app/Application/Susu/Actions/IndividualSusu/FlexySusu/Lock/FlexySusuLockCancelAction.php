<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\FlexySusu\Lock;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Account\Models\AccountPayoutLock;
use App\Domain\Account\Services\AccountLock\AccountLockStatusUpdateService;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class FlexySusuLockCancelAction
{
    private AccountLockStatusUpdateService $accountLockStatusUpdateService;

    /**
     * @param AccountLockStatusUpdateService $accountLockStatusUpdateService
     */
    public function __construct(
        AccountLockStatusUpdateService $accountLockStatusUpdateService
    ) {
        $this->accountLockStatusUpdateService = $accountLockStatusUpdateService;
    }

    /**
     * @param AccountPayoutLock $accountLock
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function execute(
        AccountPayoutLock $accountLock,
    ): JsonResponse {
        // Execute the PaymentInstructionCancelService and return the resource
        $this->accountLockStatusUpdateService->execute(
            accountLock: $accountLock,
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
