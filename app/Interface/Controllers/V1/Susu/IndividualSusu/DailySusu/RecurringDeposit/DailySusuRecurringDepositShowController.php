<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\RecurringDeposit;

use App\Application\Susu\Actions\IndividualSusu\DailySusu\RecurringDeposit\DailySusuRecurringDepositShowAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuRecurringDepositShowController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param DailySusuRecurringDepositShowAction $dailySusuRecurringDepositShowAction
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        DailySusuRecurringDepositShowAction $dailySusuRecurringDepositShowAction
    ): JsonResponse {
        // Execute the DailySusuSettlementLockCancelAction and return the JsonResponse
        return $dailySusuRecurringDepositShowAction->execute(
            customer: $customer,
            dailySusu: $dailySusu
        );
    }
}
