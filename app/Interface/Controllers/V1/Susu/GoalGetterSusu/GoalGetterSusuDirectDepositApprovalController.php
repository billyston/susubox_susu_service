<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\GoalGetterSusu;

use App\Application\Susu\Actions\GoalGetterSusu\GoalGetterSusuDirectDepositApprovalAction;
use App\Domain\Account\Models\DirectDeposit;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\GoalGetterSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\GoalGetterSusu\GoalGetterSusuDirectDepositApprovalRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GoalGetterSusuDirectDepositApprovalController extends Controller
{
    /**
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        GoalGetterSusu $goalGetterSusu,
        DirectDeposit $directDeposit,
        GoalGetterSusuDirectDepositApprovalRequest $goalGetterSusuDirectDepositApprovalRequest,
        GoalGetterSusuDirectDepositApprovalAction $goalGetterSusuDirectDepositApprovalAction
    ): JsonResponse {
        // Execute the GoalGetterSusuDirectDepositApprovalAction and return the JsonResponse
        return $goalGetterSusuDirectDepositApprovalAction->execute(
            customer: $customer,
            goal_getter_susu: $goalGetterSusu,
            direct_deposit: $directDeposit,
            request: $goalGetterSusuDirectDepositApprovalRequest
        );
    }
}
