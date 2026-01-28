<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Statistics;

use App\Application\Susu\Actions\IndividualSusu\DailySusu\Statistics\DailySusuSettlementStatisticsAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuSettlementStatisticsController extends Controller
{
    /**
     * @param Request $request
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param DailySusuSettlementStatisticsAction $dailySusuSettlementStatisticsAction
     * @return JsonResponse
     * @throws MoneyMismatchException
     * @throws UnknownCurrencyException
     */
    public function __invoke(
        Request $request,
        Customer $customer,
        DailySusu $dailySusu,
        DailySusuSettlementStatisticsAction $dailySusuSettlementStatisticsAction
    ): JsonResponse {
        // Execute the DailySusuSettlementStatisticsAction and return the JsonResponse
        return $dailySusuSettlementStatisticsAction->execute(
            dailySusu: $dailySusu,
            request: $request->all()
        );
    }
}
