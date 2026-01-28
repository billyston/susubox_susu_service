<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Statistics;

use App\Application\Susu\Actions\IndividualSusu\DailySusu\Statistics\DailySusuCycleStatisticsAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use Brick\Money\Exception\MoneyMismatchException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuCycleStatisticsController extends Controller
{
    /**
     * @param Request $request
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param DailySusuCycleStatisticsAction $dailySusuCycleStatisticsAction
     * @return JsonResponse
     * @throws MoneyMismatchException
     */
    public function __invoke(
        Request $request,
        Customer $customer,
        DailySusu $dailySusu,
        DailySusuCycleStatisticsAction $dailySusuCycleStatisticsAction
    ): JsonResponse {
        // Execute the DailySusuCycleStatisticsAction and return the JsonResponse
        return $dailySusuCycleStatisticsAction->execute(
            dailySusu: $dailySusu,
            request: $request->all()
        );
    }
}
