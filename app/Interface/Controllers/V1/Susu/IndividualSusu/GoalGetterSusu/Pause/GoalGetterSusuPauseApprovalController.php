<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\GoalGetterSusu\Pause;

use App\Application\Susu\Actions\IndividualSusu\GoalGetterSusu\Pause\GoalGetterSusuPauseApprovalAction;
use App\Domain\Account\Models\AccountPause;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\GoalGetterSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\GoalGetterSusu\Pause\GoalGetterSusuPauseApprovalRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GoalGetterSusuPauseApprovalController extends Controller
{
    /**
     * @param Customer $customer
     * @param GoalGetterSusu $goalGetterSusu
     * @param AccountPause $accountPause
     * @param GoalGetterSusuPauseApprovalRequest $goalGetterSusuPauseApprovalRequest
     * @param GoalGetterSusuPauseApprovalAction $goalGetterSusuPauseApprovalAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        GoalGetterSusu $goalGetterSusu,
        AccountPause $accountPause,
        GoalGetterSusuPauseApprovalRequest $goalGetterSusuPauseApprovalRequest,
        GoalGetterSusuPauseApprovalAction $goalGetterSusuPauseApprovalAction
    ): JsonResponse {
        // Execute the GoalGetterSusuPauseApprovalAction and return the JsonResponse
        return $goalGetterSusuPauseApprovalAction->execute(
            accountPause: $accountPause,
        );
    }
}
