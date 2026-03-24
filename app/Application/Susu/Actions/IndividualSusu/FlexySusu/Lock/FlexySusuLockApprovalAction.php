<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\FlexySusu\Lock;

use App\Application\Account\Jobs\AccountPayoutLock\AccountPayoutLockNotificationJob;
use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Account\Models\AccountPayoutLock;
use App\Domain\Account\Services\AccountPayoutLock\AccountPayoutLockStatusUpdateService;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\FlexySusu;
use App\Domain\Susu\Services\IndividualSusu\FlexySusu\Withdrawal\FlexySusuWithdrawalStatusUpdateService;
use App\Interface\Resources\V1\Account\AccountPayoutLock\AccountPayoutLockResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class FlexySusuLockApprovalAction
{
    private AccountPayoutLockStatusUpdateService $accountLockStatusUpdateService;
    private FlexySusuWithdrawalStatusUpdateService $flexySusuWithdrawalStatusUpdateService;

    public function __construct(
        AccountPayoutLockStatusUpdateService $accountLockStatusUpdateService,
        FlexySusuWithdrawalStatusUpdateService $flexySusuWithdrawalStatusUpdateService
    ) {
        $this->accountLockStatusUpdateService = $accountLockStatusUpdateService;
        $this->flexySusuWithdrawalStatusUpdateService = $flexySusuWithdrawalStatusUpdateService;
    }

    /**
     * @param Customer $customer
     * @param FlexySusu $flexySusu
     * @param AccountPayoutLock $accountLock
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function execute(
        Customer $customer,
        FlexySusu $flexySusu,
        AccountPayoutLock $accountLock,
    ): JsonResponse {
        // Execute the AccountPayoutLockStatusUpdateService
        $this->accountLockStatusUpdateService->execute(
            accountPayoutLock: $accountLock,
            status: Statuses::ACTIVE->value
        );

        // Execute the FlexySusuWithdrawalStatusUpdateService
        $this->flexySusuWithdrawalStatusUpdateService->execute(
            flexySusu: $flexySusu,
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
