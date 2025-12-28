<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\BizSusu;

use App\Application\Account\Jobs\AccountLockNotificationJob;
use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Account\Models\AccountLock;
use App\Domain\Account\Services\AccountLockStatusUpdateService;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Domain\Susu\Services\BizSusu\BizSusuWithdrawalStatusUpdateService;
use App\Interface\Resources\V1\Account\AccountLockResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class BizSusuWithdrawalLockApprovalAction
{
    private AccountLockStatusUpdateService $accountLockStatusUpdateService;
    private BizSusuWithdrawalStatusUpdateService $bizSusuWithdrawalStatusUpdateService;

    public function __construct(
        AccountLockStatusUpdateService $accountLockStatusUpdateService,
        BizSusuWithdrawalStatusUpdateService $bizSusuWithdrawalStatusUpdateService
    ) {
        $this->accountLockStatusUpdateService = $accountLockStatusUpdateService;
        $this->bizSusuWithdrawalStatusUpdateService = $bizSusuWithdrawalStatusUpdateService;
    }

    /**
     * @param Customer $customer
     * @param BizSusu $bizSusu
     * @param AccountLock $accountLock
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function execute(
        Customer $customer,
        BizSusu $bizSusu,
        AccountLock $accountLock,
    ): JsonResponse {
        // Execute the AccountLockStatusUpdateService
        $this->accountLockStatusUpdateService->execute(
            accountLock: $accountLock,
            status: Statuses::ACTIVE->value
        );

        // Execute the BizSusuWithdrawalStatusUpdateService
        $this->bizSusuWithdrawalStatusUpdateService->execute(
            bizSusu: $bizSusu,
            status: Statuses::LOCKED->value
        );

        // Dispatch the AccountLockNotificationJob
        AccountLockNotificationJob::dispatch(
            customerResource: $customer->resource_id,
            accountLockResource: $accountLock->resource_id
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'Your request is successful. You will be notified shortly.',
            data: new AccountLockResource(
                resource: $accountLock->refresh()
            )
        );
    }
}
