<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\AutoSettlement;

use App\Application\Susu\Actions\IndividualSusu\DailySusu\AutoSettlement\DailySusuAutoSettlementAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\DailySusu\AutoSettlement\DailySusuAutoSettlementRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuAutoSettlementController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param DailySusuAutoSettlementRequest $dailySusuAutoSettlementRequest
     * @param DailySusuAutoSettlementAction $dailySusuAutoSettlementAction
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        DailySusuAutoSettlementRequest $dailySusuAutoSettlementRequest,
        DailySusuAutoSettlementAction $dailySusuAutoSettlementAction
    ): JsonResponse {
        // Execute the DailySusuAutoSettlementAction and return the JsonResponse
        return $dailySusuAutoSettlementAction->execute(
            dailySusu: $dailySusu,
        );
    }
}
