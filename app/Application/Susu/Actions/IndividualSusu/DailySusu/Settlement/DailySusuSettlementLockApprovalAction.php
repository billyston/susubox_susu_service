<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\DailySusu\Settlement;

use App\Application\Account\Jobs\AccountPayoutLock\AccountPayoutLockNotificationJob;
use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Account\Models\AccountPayoutLock;
use App\Domain\Account\Services\AccountPayoutLock\AccountPayoutLockStatusUpdateService;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Domain\Susu\Services\IndividualSusu\DailySusu\Settlement\DailySusuSettlementStatusUpdateService;
use App\Interface\Resources\V1\Account\AccountPayoutLock\AccountPayoutLockResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuSettlementLockApprovalAction
{
    private AccountPayoutLockStatusUpdateService $accountPayoutLockStatusUpdateService;
    private DailySusuSettlementStatusUpdateService $dailySusuSettlementStatusUpdateService;

    public function __construct(
        AccountPayoutLockStatusUpdateService $accountPayoutLockStatusUpdateService,
        DailySusuSettlementStatusUpdateService $dailySusuSettlementStatusUpdateService
    ) {
        $this->accountPayoutLockStatusUpdateService = $accountPayoutLockStatusUpdateService;
        $this->dailySusuSettlementStatusUpdateService = $dailySusuSettlementStatusUpdateService;
    }

    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param AccountPayoutLock $accountPayoutLock
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function execute(
        Customer $customer,
        DailySusu $dailySusu,
        AccountPayoutLock $accountPayoutLock,
    ): JsonResponse {
        // Execute the AccountPayoutLockStatusUpdateService
        $this->accountPayoutLockStatusUpdateService->execute(
            accountPayoutLock: $accountPayoutLock,
            status: Statuses::ACTIVE->value
        );

        // Execute the DailySusuSettlementStatusUpdateService
        $this->dailySusuSettlementStatusUpdateService->execute(
            dailySusu: $dailySusu,
            status: Statuses::LOCKED->value
        );

        // Dispatch the AccountPayoutLockNotificationJob
        AccountPayoutLockNotificationJob::dispatch(
            customerResource: $customer->resource_id,
            accountLockResource: $accountPayoutLock->resource_id
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'Your request is successful. You will be notified shortly.',
            data: new AccountPayoutLockResource(
                resource: $accountPayoutLock->refresh()
            )
        );
    }
}
