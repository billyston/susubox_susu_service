<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Account;

use App\Application\Susu\Actions\IndividualSusu\DailySusu\Account\DailySusuReactivationAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\DailySusu\Account\DailySusuReactivationRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuReactivationController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param DailySusuReactivationRequest $dailySusuReactivationRequest
     * @param DailySusuReactivationAction $dailySusuReactivationAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        DailySusuReactivationRequest $dailySusuReactivationRequest,
        DailySusuReactivationAction $dailySusuReactivationAction
    ): JsonResponse {
        // Execute the DailySusuReactivationAction and return the JsonResponse
        return $dailySusuReactivationAction->execute(
            dailySusu: $dailySusu,
        );
    }
}
