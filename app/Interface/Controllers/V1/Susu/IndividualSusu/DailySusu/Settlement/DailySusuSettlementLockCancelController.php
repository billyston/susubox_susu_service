<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Settlement;

use App\Application\Susu\Actions\IndividualSusu\DailySusu\Settlement\DailySusuSettlementLockCancelAction;
use App\Domain\Account\Models\AccountPayoutLock;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\DailySusu\SettlementLock\DailySusuSettlementLockCancelRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuSettlementLockCancelController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param AccountPayoutLock $accountPayoutLock
     * @param DailySusuSettlementLockCancelRequest $dailySusuSettlementLockCancelRequest
     * @param DailySusuSettlementLockCancelAction $dailySusuSettlementLockCancelAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        AccountPayoutLock $accountPayoutLock,
        DailySusuSettlementLockCancelRequest $dailySusuSettlementLockCancelRequest,
        DailySusuSettlementLockCancelAction $dailySusuSettlementLockCancelAction
    ): JsonResponse {
        // Execute the DailySusuSettlementLockCancelAction and return the JsonResponse
        return $dailySusuSettlementLockCancelAction->execute(
            accountPayoutLock: $accountPayoutLock,
        );
    }
}
