<?php

declare(strict_types=1);

namespace App\Interface\Http\Controllers\V1\Susu\GoalGetterSusu;

use App\Application\Susu\Actions\GoalGetterSusu\GoalGetterSusuShowAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\GoalGetterSusu;
use App\Interface\Http\Controllers\Controller;
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
