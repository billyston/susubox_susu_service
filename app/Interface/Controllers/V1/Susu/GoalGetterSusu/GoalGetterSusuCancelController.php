<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\GoalGetterSusu;

use App\Application\Susu\Actions\GoalGetterSusu\GoalGetterSusuCancelAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\CancellationNotAllowedException;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\GoalGetterSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\GoalGetterSusu\GoalGetterSusuCancelRequest;
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
            request: $goalGetterSusuCancelRequest->validated()
        );
    }
}
