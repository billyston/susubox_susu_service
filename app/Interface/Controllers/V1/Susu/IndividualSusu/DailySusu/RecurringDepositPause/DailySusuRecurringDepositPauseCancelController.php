<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\RecurringDepositPause;

use App\Application\Susu\Actions\IndividualSusu\DailySusu\RecurringDepositPause\DailySusuRecurringDepositPauseCancelAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Models\RecurringDepositPause;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\DailySusu\RecurringDepositPause\DailySusuRecurringDepositPauseCancelRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuRecurringDepositPauseCancelController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param RecurringDepositPause $recurringDepositPause
     * @param DailySusuRecurringDepositPauseCancelRequest $dailySusuRecurringDepositPauseCancelRequest
     * @param DailySusuRecurringDepositPauseCancelAction $dailySusuRecurringDepositPauseCancelAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        RecurringDepositPause $recurringDepositPause,
        DailySusuRecurringDepositPauseCancelRequest $dailySusuRecurringDepositPauseCancelRequest,
        DailySusuRecurringDepositPauseCancelAction $dailySusuRecurringDepositPauseCancelAction
    ): JsonResponse {
        // Execute the DailySusuSettlementLockCancelAction and return the JsonResponse
        return $dailySusuRecurringDepositPauseCancelAction->execute(
            recurringDepositPause: $recurringDepositPause,
        );
    }
}
