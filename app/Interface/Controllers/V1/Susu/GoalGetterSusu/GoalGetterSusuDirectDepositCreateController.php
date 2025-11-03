<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\GoalGetterSusu;

use App\Application\Susu\Actions\GoalGetterSusu\GoalGetterSusuDirectDepositCreateAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\GoalGetterSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\GoalGetterSusu\GoalGetterSusuDirectDepositCreateRequest;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GoalGetterSusuDirectDepositCreateController extends Controller
{
    /**
     * @throws SystemFailureException
     * @throws UnknownCurrencyException
     */
    public function __invoke(
        Customer $customer,
        GoalGetterSusu $goal_getter_susu,
        GoalGetterSusuDirectDepositCreateRequest $goalGetterSusuDirectDepositCreateRequest,
        GoalGetterSusuDirectDepositCreateAction $goalGetterSusuDirectDepositCreateAction
    ): JsonResponse {
        // Execute the GoalGetterSusuDirectDepositCreateAction and return the JsonResponse
        return $goalGetterSusuDirectDepositCreateAction->execute(
            customer: $customer,
            goal_getter_susu: $goal_getter_susu,
            request: $goalGetterSusuDirectDepositCreateRequest
        );
    }
}
