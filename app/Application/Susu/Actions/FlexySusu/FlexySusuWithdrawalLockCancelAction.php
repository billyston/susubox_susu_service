<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\FlexySusu;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Account\Models\AccountLock;
use App\Domain\Account\Services\AccountLockStatusUpdateService;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\FlexySusu;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class FlexySusuWithdrawalLockCancelAction
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
     * @param Customer $customer
     * @param FlexySusu $flexySusu
     * @param AccountLock $accountLock
     * @param array $request
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function execute(
        Customer $customer,
        FlexySusu $flexySusu,
        AccountLock $accountLock,
        array $request
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
