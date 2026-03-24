<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\RecurringDepositPause;

use App\Application\Susu\Actions\IndividualSusu\DailySusu\RecurringDepositPause\DailySusuRecurringDepositPauseCreateAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\DailySusu\RecurringDepositPause\DailySusuRecurringDepositPauseCreateRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuRecurringDepositPauseCreateController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param DailySusuRecurringDepositPauseCreateRequest $dailySusuRecurringDepositPauseCreateRequest
     * @param DailySusuRecurringDepositPauseCreateAction $dailySusuRecurringDepositPauseCreateAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        DailySusuRecurringDepositPauseCreateRequest $dailySusuRecurringDepositPauseCreateRequest,
        DailySusuRecurringDepositPauseCreateAction $dailySusuRecurringDepositPauseCreateAction
    ): JsonResponse {
        // Execute the DailySusuRecurringDepositPauseCreateAction and return the JsonResponse
        return $dailySusuRecurringDepositPauseCreateAction->execute(
            dailySusu: $dailySusu,
            request: $dailySusuRecurringDepositPauseCreateRequest->validated()
        );
    }
}
