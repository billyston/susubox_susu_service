<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\CycleDefinition;

use App\Application\Susu\Actions\IndividualSusu\DailySusu\CycleDefinition\DailySusuCycleDefinitionShowAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuCycleDefinitionShowController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param Request $request
     * @param DailySusuCycleDefinitionShowAction $dailySusuCycleDefinitionShowAction
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        Request $request,
        DailySusuCycleDefinitionShowAction $dailySusuCycleDefinitionShowAction
    ): JsonResponse {
        // Execute the DailySusuCycleDefinitionShowAction and return the JsonResponse
        return $dailySusuCycleDefinitionShowAction->execute(
            customer: $customer,
            dailySusu: $dailySusu,
        );
    }
}
