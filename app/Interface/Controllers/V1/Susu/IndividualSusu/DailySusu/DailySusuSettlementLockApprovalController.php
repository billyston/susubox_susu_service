<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu;

use App\Application\Susu\Actions\DailySusu\DailySusuSettlementLockApprovalAction;
use App\Domain\Account\Models\AccountLock;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\DailySusu\DailySusuSettlementLockApprovalRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuSettlementLockApprovalController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param AccountLock $accountLock
     * @param DailySusuSettlementLockApprovalRequest $dailySusuSettlementLockApprovalRequest
     * @param DailySusuSettlementLockApprovalAction $dailySusuSettlementLockApprovalAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        AccountLock $accountLock,
        DailySusuSettlementLockApprovalRequest $dailySusuSettlementLockApprovalRequest,
        DailySusuSettlementLockApprovalAction $dailySusuSettlementLockApprovalAction
    ): JsonResponse {
        // Execute the DailySusuSettlementLockApprovalAction and return the JsonResponse
        return $dailySusuSettlementLockApprovalAction->execute(
            customer: $customer,
            dailySusu: $dailySusu,
            accountLock: $accountLock,
        );
    }
}
