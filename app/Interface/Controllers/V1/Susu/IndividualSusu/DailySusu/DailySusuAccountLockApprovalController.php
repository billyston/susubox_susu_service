<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu;

use App\Application\Susu\Actions\DailySusu\DailySusuAccountLockApprovalAction;
use App\Domain\Account\Models\AccountLock;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\DailySusu\DailySusuAccountLockApprovalRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuAccountLockApprovalController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param AccountLock $accountLock
     * @param DailySusuAccountLockApprovalRequest $dailySusuAccountLockApprovalRequest
     * @param DailySusuAccountLockApprovalAction $dailySusuAccountLockApprovalAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        AccountLock $accountLock,
        DailySusuAccountLockApprovalRequest $dailySusuAccountLockApprovalRequest,
        DailySusuAccountLockApprovalAction $dailySusuAccountLockApprovalAction
    ): JsonResponse {
        // Execute the DailySusuAccountLockApprovalAction and return the JsonResponse
        return $dailySusuAccountLockApprovalAction->execute(
            customer: $customer,
            dailySusu: $dailySusu,
            accountLock: $accountLock,
        );
    }
}
