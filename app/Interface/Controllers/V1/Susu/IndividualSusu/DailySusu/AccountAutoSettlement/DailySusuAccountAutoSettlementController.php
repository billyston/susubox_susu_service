<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\AccountAutoSettlement;

use App\Application\Susu\Actions\IndividualSusu\DailySusu\AccountAutoSettlement\DailySusuAccountAutoSettlementAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\DailySusu\AccountAutoSettlement\DailySusuAccountAutoSettlementRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuAccountAutoSettlementController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param DailySusuAccountAutoSettlementRequest $dailySusuAccountAutoSettlementRequest
     * @param DailySusuAccountAutoSettlementAction $dailySusuAccountAutoSettlementAction
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        DailySusuAccountAutoSettlementRequest $dailySusuAccountAutoSettlementRequest,
        DailySusuAccountAutoSettlementAction $dailySusuAccountAutoSettlementAction
    ): JsonResponse {
        // Execute the DailySusuAccountAutoSettlementAction and return the JsonResponse
        return $dailySusuAccountAutoSettlementAction->execute(
            dailySusu: $dailySusu,
        );
    }
}
