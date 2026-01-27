<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Settlement;

use App\Application\Susu\Actions\IndividualSusu\DailySusu\Settlement\DailySusuSettlementCancelAction;
use App\Domain\Account\Models\AccountSettlement;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\DailySusu\Settlement\DailySusuSettlementCancelRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuSettlementCancelController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param AccountSettlement $accountSettlement
     * @param DailySusuSettlementCancelRequest $dailySusuSettlementCancelRequest
     * @param DailySusuSettlementCancelAction $dailySusuSettlementCancelAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        AccountSettlement $accountSettlement,
        DailySusuSettlementCancelRequest $dailySusuSettlementCancelRequest,
        DailySusuSettlementCancelAction $dailySusuSettlementCancelAction
    ): JsonResponse {
        // Execute the DailySusuSettlementCancelAction and return the JsonResponse
        return $dailySusuSettlementCancelAction->execute(
            accountSettlement: $accountSettlement,
        );
    }
}
