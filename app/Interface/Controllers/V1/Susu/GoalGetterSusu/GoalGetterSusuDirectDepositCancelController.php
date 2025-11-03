<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\GoalGetterSusu;

use App\Application\Susu\Actions\GoalGetterSusu\GoalGetterSusuDirectDepositCancelAction;
use App\Domain\Account\Models\DirectDeposit;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\GoalGetterSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\GoalGetterSusu\GoalGetterSusuDirectDepositCancelRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GoalGetterSusuDirectDepositCancelController extends Controller
{
    /**
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        GoalGetterSusu $goalGetterSusu,
        DirectDeposit $directDeposit,
        GoalGetterSusuDirectDepositCancelRequest $goalGetterSusuDirectDepositCancelRequest,
        GoalGetterSusuDirectDepositCancelAction $goalGetterSusuDirectDepositCancelAction
    ): JsonResponse {
        // Execute the GoalGetterSusuDirectDepositCancelAction and return the JsonResponse
        return $goalGetterSusuDirectDepositCancelAction->execute(
            customer: $customer,
            goal_getter_susu: $goalGetterSusu,
            direct_deposit: $directDeposit,
            request: $goalGetterSusuDirectDepositCancelRequest
        );
    }
}
