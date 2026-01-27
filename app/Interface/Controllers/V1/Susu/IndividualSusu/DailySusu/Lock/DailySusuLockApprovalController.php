<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Lock;

use App\Application\Susu\Actions\IndividualSusu\DailySusu\Lock\DailySusuLockApprovalAction;
use App\Domain\Account\Models\AccountLock;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\DailySusu\Lock\DailySusuLockApprovalRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuLockApprovalController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param AccountLock $accountLock
     * @param DailySusuLockApprovalRequest $dailySusuLockApprovalRequest
     * @param DailySusuLockApprovalAction $dailySusuLockApprovalAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        AccountLock $accountLock,
        DailySusuLockApprovalRequest $dailySusuLockApprovalRequest,
        DailySusuLockApprovalAction $dailySusuLockApprovalAction
    ): JsonResponse {
        // Execute the DailySusuLockApprovalAction and return the JsonResponse
        return $dailySusuLockApprovalAction->execute(
            customer: $customer,
            dailySusu: $dailySusu,
            accountLock: $accountLock,
        );
    }
}
