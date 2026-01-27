<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Statistics;

use App\Application\Susu\Actions\IndividualSusu\DailySusu\Statistics\DailySusuStatisticsAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use Brick\Money\Exception\MoneyMismatchException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuStatisticsController extends Controller
{
    /**
     * @param Request $request
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param DailySusuStatisticsAction $dailySusuAccountStatsAction
     * @return JsonResponse
     * @throws MoneyMismatchException
     */
    public function __invoke(
        Request $request,
        Customer $customer,
        DailySusu $dailySusu,
        DailySusuStatisticsAction $dailySusuAccountStatsAction
    ): JsonResponse {
        // Execute the DailySusuStatisticsAction and return the JsonResponse
        return $dailySusuAccountStatsAction->execute(
            dailySusu: $dailySusu,
            request: $request->all()
        );
    }
}
