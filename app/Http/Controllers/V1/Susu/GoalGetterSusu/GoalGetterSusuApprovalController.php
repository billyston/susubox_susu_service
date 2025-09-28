<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Susu\GoalGetterSusu;

use App\Exceptions\Common\SystemFailureException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Susu\GoalGetterSusu\GoalGetterSusuApprovalRequest;
use Domain\Customer\Exceptions\LinkedWalletNotFoundException;
use Domain\Customer\Models\Customer;
use Domain\Shared\Exceptions\FrequencyNotFoundException;
use Domain\Shared\Exceptions\SusuSchemeNotFoundException;
use Domain\Susu\Actions\GoalGetterSusu\GoalGetterSusuApprovalAction;
use Domain\Susu\Models\Account;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GoalGetterSusuApprovalController extends Controller
{
    /**
     * @throws SystemFailureException
     * @throws LinkedWalletNotFoundException
     * @throws SusuSchemeNotFoundException
     * @throws FrequencyNotFoundException
     */
    public function __invoke(
        Customer $customer,
        Account $account,
        GoalGetterSusuApprovalRequest $goalGetterSusuApprovalRequest,
        GoalGetterSusuApprovalAction $goalGetterSusuApprovalAction
    ): JsonResponse {
        // Execute the GoalGetterSusuApprovalAction and return the JsonResponse
        return $goalGetterSusuApprovalAction->execute(
            customer: $customer,
            account: $account,
            goalGetterSusuApprovalRequest: $goalGetterSusuApprovalRequest
        );
    }
}
