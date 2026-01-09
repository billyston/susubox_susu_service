<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\AccountCycle;

use App\Application\Susu\Actions\DailySusu\AccountCycle\DailySusuAccountCycleShowAction;
use App\Domain\Account\Models\AccountCycle;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuAccountCycleShowController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param AccountCycle $accountCycle
     * @param DailySusuAccountCycleShowAction $dailySusuAccountCycleShowAction
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        AccountCycle $accountCycle,
        DailySusuAccountCycleShowAction $dailySusuAccountCycleShowAction
    ): JsonResponse {
        // Execute the DailySusuAccountCycleShowAction and return the JsonResponse
        return $dailySusuAccountCycleShowAction->execute(
            customer: $customer,
            dailySusu: $dailySusu,
            accountCycle: $accountCycle
        );
    }
}
