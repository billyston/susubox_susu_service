<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\GoalGetterSusu;

use App\Application\Susu\Actions\GoalGetterSusu\GoalGetterSusuAccountPauseApprovalAction;
use App\Domain\Account\Models\AccountPause;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\GoalGetterSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\GoalGetterSusu\GoalGetterSusuAccountPauseApprovalRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GoalGetterSusuAccountPauseApprovalController extends Controller
{
    /**
     * @param Customer $customer
     * @param GoalGetterSusu $goalGetterSusu
     * @param AccountPause $accountPause
     * @param GoalGetterSusuAccountPauseApprovalRequest $goalGetterSusuAccountPauseApprovalRequest
     * @param GoalGetterSusuAccountPauseApprovalAction $goalGetterSusuAccountPauseApprovalAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        GoalGetterSusu $goalGetterSusu,
        AccountPause $accountPause,
        GoalGetterSusuAccountPauseApprovalRequest $goalGetterSusuAccountPauseApprovalRequest,
        GoalGetterSusuAccountPauseApprovalAction $goalGetterSusuAccountPauseApprovalAction
    ): JsonResponse {
        // Execute the GoalGetterSusuAccountPauseApprovalAction and return the JsonResponse
        return $goalGetterSusuAccountPauseApprovalAction->execute(
            customer: $customer,
            goalGetterSusu: $goalGetterSusu,
            accountPause: $accountPause,
        );
    }
}
