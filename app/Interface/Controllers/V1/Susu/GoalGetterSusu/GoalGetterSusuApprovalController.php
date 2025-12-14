<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\GoalGetterSusu;

use App\Application\Susu\Actions\GoalGetterSusu\GoalGetterSusuApprovalAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\GoalGetterSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\GoalGetterSusu\GoalGetterSusuApprovalRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GoalGetterSusuApprovalController extends Controller
{
    /**
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        GoalGetterSusu $goalGetterSusu,
        GoalGetterSusuApprovalRequest $goalGetterSusuApprovalRequest,
        GoalGetterSusuApprovalAction $goalGetterSusuApprovalAction
    ): JsonResponse {
        // Execute the GoalGetterSusuApprovalAction and return the JsonResponse
        return $goalGetterSusuApprovalAction->execute(
            customer: $customer,
            goalGetterSusu: $goalGetterSusu,
        );
    }
}
