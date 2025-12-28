<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\DailySusu;

use App\Application\Account\Jobs\AccountLockNotificationJob;
use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Account\Models\AccountLock;
use App\Domain\Account\Services\AccountLockStatusUpdateService;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Domain\Susu\Services\DailySusu\DailySusuSettlementStatusUpdateService;
use App\Interface\Resources\V1\Account\AccountLockResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuSettlementLockApprovalAction
{
    private AccountLockStatusUpdateService $accountLockStatusUpdateService;
    private DailySusuSettlementStatusUpdateService $dailySusuSettlementStatusUpdateService;

    public function __construct(
        AccountLockStatusUpdateService $accountLockStatusUpdateService,
        DailySusuSettlementStatusUpdateService $dailySusuSettlementStatusUpdateService
    ) {
        $this->accountLockStatusUpdateService = $accountLockStatusUpdateService;
        $this->dailySusuSettlementStatusUpdateService = $dailySusuSettlementStatusUpdateService;
    }

    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param AccountLock $accountLock
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function execute(
        Customer $customer,
        DailySusu $dailySusu,
        AccountLock $accountLock,
    ): JsonResponse {
        // Execute the AccountLockStatusUpdateService
        $this->accountLockStatusUpdateService->execute(
            accountLock: $accountLock,
            status: Statuses::ACTIVE->value
        );

        // Execute the DailySusuSettlementStatusUpdateService
        $this->dailySusuSettlementStatusUpdateService->execute(
            dailySusu: $dailySusu,
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
