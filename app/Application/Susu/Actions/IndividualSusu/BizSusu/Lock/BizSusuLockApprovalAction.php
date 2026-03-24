<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\BizSusu\Lock;

use App\Application\Account\Jobs\AccountPayoutLock\AccountPayoutLockNotificationJob;
use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Account\Models\AccountPayoutLock;
use App\Domain\Account\Services\AccountPayoutLock\AccountPayoutLockStatusUpdateService;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Domain\Susu\Services\IndividualSusu\BizSusu\Withdrawal\BizSusuWithdrawalStatusUpdateService;
use App\Interface\Resources\V1\Account\AccountPayoutLock\AccountPayoutLockResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class BizSusuLockApprovalAction
{
    private AccountPayoutLockStatusUpdateService $accountLockStatusUpdateService;
    private BizSusuWithdrawalStatusUpdateService $bizSusuWithdrawalStatusUpdateService;

    public function __construct(
        AccountPayoutLockStatusUpdateService $accountLockStatusUpdateService,
        BizSusuWithdrawalStatusUpdateService $bizSusuWithdrawalStatusUpdateService
    ) {
        $this->accountLockStatusUpdateService = $accountLockStatusUpdateService;
        $this->bizSusuWithdrawalStatusUpdateService = $bizSusuWithdrawalStatusUpdateService;
    }

    /**
     * @param Customer $customer
     * @param BizSusu $bizSusu
     * @param AccountPayoutLock $accountLock
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function execute(
        Customer $customer,
        BizSusu $bizSusu,
        AccountPayoutLock $accountLock,
    ): JsonResponse {
        // Execute the AccountPayoutLockStatusUpdateService
        $this->accountLockStatusUpdateService->execute(
            accountPayoutLock: $accountLock,
            status: Statuses::ACTIVE->value
        );

        // Execute the BizSusuWithdrawalStatusUpdateService
        $this->bizSusuWithdrawalStatusUpdateService->execute(
            bizSusu: $bizSusu,
            status: Statuses::LOCKED->value
        );

        // Dispatch the AccountPayoutLockNotificationJob
        AccountPayoutLockNotificationJob::dispatch(
            customerResource: $customer->resource_id,
            accountLockResource: $accountLock->resource_id
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'Your request is successful. You will be notified shortly.',
            data: new AccountPayoutLockResource(
                resource: $accountLock->refresh()
            )
        );
    }
}
