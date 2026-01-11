<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\AccountSettlement;

use App\Application\Susu\Actions\DailySusu\AccountSettlement\DailySusuAccountSettlementCancelAction;
use App\Domain\Account\Models\AccountSettlement;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\DailySusu\AccountSettlement\DailySusuAccountSettlementCancelRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuAccountSettlementCancelController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param AccountSettlement $accountSettlement
     * @param DailySusuAccountSettlementCancelRequest $dailySusuAccountSettlementCancelRequest
     * @param DailySusuAccountSettlementCancelAction $dailySusuAccountSettlementCancelAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        AccountSettlement $accountSettlement,
        DailySusuAccountSettlementCancelRequest $dailySusuAccountSettlementCancelRequest,
        DailySusuAccountSettlementCancelAction $dailySusuAccountSettlementCancelAction
    ): JsonResponse {
        // Execute the DailySusuAccountSettlementCancelAction and return the JsonResponse
        return $dailySusuAccountSettlementCancelAction->execute(
            accountSettlement: $accountSettlement,
        );
    }
}
