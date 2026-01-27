<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Cycle;

use App\Application\Susu\Actions\IndividualSusu\DailySusu\Cycle\DailySusuCycleIndexAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuCycleIndexController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param DailySusuCycleIndexAction $dailySusuCycleIndexAction
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        DailySusuCycleIndexAction $dailySusuCycleIndexAction
    ): JsonResponse {
        // Execute the DailySusuCycleIndexAction and return the JsonResponse
        return $dailySusuCycleIndexAction->execute(
            customer: $customer,
            dailySusu: $dailySusu,
        );
    }
}
