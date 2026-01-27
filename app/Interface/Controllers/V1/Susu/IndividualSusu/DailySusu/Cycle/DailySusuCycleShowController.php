<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Cycle;

use App\Application\Susu\Actions\IndividualSusu\DailySusu\Cycle\DailySusuCycleShowAction;
use App\Domain\Account\Models\AccountCycle;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuCycleShowController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param AccountCycle $accountCycle
     * @param DailySusuCycleShowAction $dailySusuCycleShowAction
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        AccountCycle $accountCycle,
        DailySusuCycleShowAction $dailySusuCycleShowAction
    ): JsonResponse {
        // Execute the DailySusuCycleShowAction and return the JsonResponse
        return $dailySusuCycleShowAction->execute(
            customer: $customer,
            dailySusu: $dailySusu,
            accountCycle: $accountCycle
        );
    }
}
