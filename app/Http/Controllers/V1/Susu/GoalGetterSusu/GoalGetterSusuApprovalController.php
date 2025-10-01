<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Susu\GoalGetterSusu;

use App\Exceptions\Common\SystemFailureException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Susu\GoalGetterSusu\GoalGetterSusuApprovalRequest;
use Domain\Customer\Models\Customer;
use Domain\Susu\Actions\GoalGetterSusu\GoalGetterSusuApprovalAction;
use Domain\Susu\Models\GoalGetterSusu;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GoalGetterSusuApprovalController extends Controller
{
    /**
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        GoalGetterSusu $goal_getter_susu,
        GoalGetterSusuApprovalRequest $goalGetterSusuApprovalRequest,
        GoalGetterSusuApprovalAction $goalGetterSusuApprovalAction
    ): JsonResponse {
        // Execute the GoalGetterSusuApprovalAction and return the JsonResponse
        return $goalGetterSusuApprovalAction->execute(
            customer: $customer,
            goal_getter_susu: $goal_getter_susu,
            goalGetterSusuApprovalRequest: $goalGetterSusuApprovalRequest
        );
    }
}
