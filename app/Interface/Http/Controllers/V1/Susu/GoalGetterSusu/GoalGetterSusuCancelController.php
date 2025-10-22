<?php

declare(strict_types=1);

namespace App\Interface\Http\Controllers\V1\Susu\GoalGetterSusu;

use App\Application\Susu\Actions\GoalGetterSusu\GoalGetterSusuCancelAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\CancellationNotAllowedException;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\GoalGetterSusu;
use App\Interface\Http\Controllers\Controller;
use App\Interface\Http\Requests\V1\Susu\GoalGetterSusu\GoalGetterSusuCancelRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GoalGetterSusuCancelController extends Controller
{
    /**
     * @throws SystemFailureException
     * @throws CancellationNotAllowedException
     */
    public function __invoke(
        Customer $customer,
        GoalGetterSusu $goal_getter_susu,
        GoalGetterSusuCancelRequest $goalGetterSusuCancelRequest,
        GoalGetterSusuCancelAction $goalGetterSusuCancelAction
    ): JsonResponse {
        // Execute the GoalGetterSusuCancelAction and return the JsonResponse
        return $goalGetterSusuCancelAction->execute(
            customer: $customer,
            goal_getter_susu: $goal_getter_susu,
            goalGetterSusuCancelRequest: $goalGetterSusuCancelRequest
        );
    }
}
