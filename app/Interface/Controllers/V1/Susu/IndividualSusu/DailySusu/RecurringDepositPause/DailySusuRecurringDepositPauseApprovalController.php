<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\RecurringDepositPause;

use App\Application\Susu\Actions\IndividualSusu\DailySusu\RecurringDepositPause\DailySusuRecurringDepositPauseApprovalAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Models\RecurringDepositPause;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\DailySusu\RecurringDepositPause\DailySusuRecurringDepositPauseApprovalRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuRecurringDepositPauseApprovalController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param RecurringDepositPause $recurringDepositPause
     * @param DailySusuRecurringDepositPauseApprovalRequest $dailySusuRecurringDepositPauseApprovalRequest
     * @param DailySusuRecurringDepositPauseApprovalAction $dailySusuRecurringDepositPauseApprovalAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        RecurringDepositPause $recurringDepositPause,
        DailySusuRecurringDepositPauseApprovalRequest $dailySusuRecurringDepositPauseApprovalRequest,
        DailySusuRecurringDepositPauseApprovalAction $dailySusuRecurringDepositPauseApprovalAction
    ): JsonResponse {
        // Execute the DailySusuRecurringDepositPauseApprovalAction and return the JsonResponse
        return $dailySusuRecurringDepositPauseApprovalAction->execute(
            recurringDepositPause: $recurringDepositPause,
        );
    }
}
