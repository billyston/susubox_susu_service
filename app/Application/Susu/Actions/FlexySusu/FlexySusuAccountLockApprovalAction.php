<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\FlexySusu;

use App\Application\Account\Jobs\AccountLockNotificationJob;
use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Account\Models\AccountLock;
use App\Domain\Account\Services\AccountLockStatusUpdateService;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\FlexySusu;
use App\Domain\Susu\Services\FlexySusu\FlexySusuWithdrawalStatusUpdateService;
use App\Interface\Resources\V1\Account\AccountLockResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class FlexySusuAccountLockApprovalAction
{
    private AccountLockStatusUpdateService $accountLockStatusUpdateService;
    private FlexySusuWithdrawalStatusUpdateService $flexySusuWithdrawalStatusUpdateService;

    public function __construct(
        AccountLockStatusUpdateService $accountLockStatusUpdateService,
        FlexySusuWithdrawalStatusUpdateService $flexySusuWithdrawalStatusUpdateService
    ) {
        $this->accountLockStatusUpdateService = $accountLockStatusUpdateService;
        $this->flexySusuWithdrawalStatusUpdateService = $flexySusuWithdrawalStatusUpdateService;
    }

    /**
     * @param Customer $customer
     * @param FlexySusu $flexySusu
     * @param AccountLock $accountLock
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function execute(
        Customer $customer,
        FlexySusu $flexySusu,
        AccountLock $accountLock,
    ): JsonResponse {
        // Execute the AccountLockStatusUpdateService
        $this->accountLockStatusUpdateService->execute(
            accountLock: $accountLock,
            status: Statuses::ACTIVE->value
        );

        // Execute the FlexySusuWithdrawalStatusUpdateService
        $this->flexySusuWithdrawalStatusUpdateService->execute(
            flexySusu: $flexySusu,
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
