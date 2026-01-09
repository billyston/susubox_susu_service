<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\AccountCycle;

use App\Application\Susu\Actions\DailySusu\AccountCycle\DailySusuAccountCycleIndexAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuAccountCycleIndexController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param DailySusuAccountCycleIndexAction $dailySusuAccountCycleIndexAction
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        DailySusuAccountCycleIndexAction $dailySusuAccountCycleIndexAction
    ): JsonResponse {
        // Execute the DailySusuAccountCycleIndexAction and return the JsonResponse
        return $dailySusuAccountCycleIndexAction->execute(
            customer: $customer,
            dailySusu: $dailySusu,
        );
    }
}
