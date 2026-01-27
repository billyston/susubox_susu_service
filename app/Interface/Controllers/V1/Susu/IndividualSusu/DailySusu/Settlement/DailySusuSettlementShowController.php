<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Settlement;

use App\Application\Susu\Actions\IndividualSusu\DailySusu\Settlement\DailySusuSettlementShowAction;
use App\Domain\Account\Models\AccountSettlement;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuSettlementShowController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param AccountSettlement $accountSettlement
     * @param DailySusuSettlementShowAction $dailySusuSettlementShowAction
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        AccountSettlement $accountSettlement,
        DailySusuSettlementShowAction $dailySusuSettlementShowAction
    ): JsonResponse {
        // Execute the DailySusuSettlementShowAction and return the JsonResponse
        return $dailySusuSettlementShowAction->execute(
            customer: $customer,
            dailySusu: $dailySusu,
            accountSettlement: $accountSettlement
        );
    }
}
