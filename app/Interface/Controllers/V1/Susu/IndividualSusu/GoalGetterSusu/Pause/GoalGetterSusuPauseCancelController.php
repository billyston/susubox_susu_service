<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\GoalGetterSusu\Pause;

use App\Application\Susu\Actions\IndividualSusu\GoalGetterSusu\Pause\GoalGetterSusuPauseCancelAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Models\RecurringDepositPause;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\GoalGetterSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\GoalGetterSusu\Pause\GoalGetterSusuPauseCancelRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GoalGetterSusuPauseCancelController extends Controller
{
    /**
     * @param Customer $customer
     * @param GoalGetterSusu $goalGetterSusu
     * @param RecurringDepositPause $accountPause
     * @param GoalGetterSusuPauseCancelRequest $goalGetterSusuPauseCancelRequest
     * @param GoalGetterSusuPauseCancelAction $goalGetterSusuPauseCancelAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        GoalGetterSusu $goalGetterSusu,
        RecurringDepositPause $accountPause,
        GoalGetterSusuPauseCancelRequest $goalGetterSusuPauseCancelRequest,
        GoalGetterSusuPauseCancelAction $goalGetterSusuPauseCancelAction
    ): JsonResponse {
        // Execute the GoalGetterSusuPauseCancelAction and return the JsonResponse
        return $goalGetterSusuPauseCancelAction->execute(
            accountPause: $accountPause
        );
    }
}
