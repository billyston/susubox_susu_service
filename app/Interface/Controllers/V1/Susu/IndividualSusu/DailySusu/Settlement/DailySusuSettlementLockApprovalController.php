<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Settlement;

use App\Application\Susu\Actions\IndividualSusu\DailySusu\Settlement\DailySusuSettlementLockApprovalAction;
use App\Domain\Account\Models\AccountPayoutLock;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\DailySusu\SettlementLock\DailySusuSettlementLockApprovalRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuSettlementLockApprovalController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param AccountPayoutLock $accountPayoutLock
     * @param DailySusuSettlementLockApprovalRequest $dailySusuSettlementLockApprovalRequest
     * @param DailySusuSettlementLockApprovalAction $dailySusuSettlementLockApprovalAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        AccountPayoutLock $accountPayoutLock,
        DailySusuSettlementLockApprovalRequest $dailySusuSettlementLockApprovalRequest,
        DailySusuSettlementLockApprovalAction $dailySusuSettlementLockApprovalAction
    ): JsonResponse {
        // Execute the DailySusuSettlementLockApprovalAction and return the JsonResponse
        return $dailySusuSettlementLockApprovalAction->execute(
            customer: $customer,
            dailySusu: $dailySusu,
            accountPayoutLock: $accountPayoutLock,
        );
    }
}
