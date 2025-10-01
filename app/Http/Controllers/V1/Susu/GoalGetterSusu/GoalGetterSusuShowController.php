<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Susu\GoalGetterSusu;

use App\Exceptions\Common\SystemFailureException;
use App\Http\Controllers\Controller;
use Domain\Customer\Models\Customer;
use Domain\Shared\Exceptions\UnauthorisedAccessException;
use Domain\Susu\Actions\GoalGetterSusu\GoalGetterSusuShowAction;
use Domain\Susu\Models\GoalGetterSusu;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GoalGetterSusuShowController extends Controller
{
    /**
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function __invoke(
        Customer $customer,
        GoalGetterSusu $goal_getter_susu,
        GoalGetterSusuShowAction $goalGetterSusuShowAction
    ): JsonResponse {
        // Execute the GoalGetterSusuShowAction and return the JsonResponse
        return $goalGetterSusuShowAction->execute(
            customer: $customer,
            goal_getter_susu: $goal_getter_susu,
        );
    }
}
