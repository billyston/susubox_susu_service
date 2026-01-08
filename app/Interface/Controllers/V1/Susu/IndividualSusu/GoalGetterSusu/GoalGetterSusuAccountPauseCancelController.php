<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\GoalGetterSusu;

use App\Application\Susu\Actions\GoalGetterSusu\GoalGetterSusuAccountPauseCancelAction;
use App\Domain\Account\Models\AccountPause;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\GoalGetterSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\GoalGetterSusu\GoalGetterSusuAccountPauseCancelRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GoalGetterSusuAccountPauseCancelController extends Controller
{
    /**
     * @param Customer $customer
     * @param GoalGetterSusu $goalGetterSusu
     * @param AccountPause $accountPause
     * @param GoalGetterSusuAccountPauseCancelRequest $goalGetterSusuAccountPauseCancelRequest
     * @param GoalGetterSusuAccountPauseCancelAction $goalGetterSusuAccountPauseCancelAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        GoalGetterSusu $goalGetterSusu,
        AccountPause $accountPause,
        GoalGetterSusuAccountPauseCancelRequest $goalGetterSusuAccountPauseCancelRequest,
        GoalGetterSusuAccountPauseCancelAction $goalGetterSusuAccountPauseCancelAction
    ): JsonResponse {
        // Execute the GoalGetterSusuAccountPauseCancelAction and return the JsonResponse
        return $goalGetterSusuAccountPauseCancelAction->execute(
            accountPause: $accountPause
        );
    }
}
