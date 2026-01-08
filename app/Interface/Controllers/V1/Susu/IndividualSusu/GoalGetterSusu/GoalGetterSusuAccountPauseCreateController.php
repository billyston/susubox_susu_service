<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\GoalGetterSusu;

use App\Application\Susu\Actions\GoalGetterSusu\GoalGetterSusuAccountPauseCreateAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\GoalGetterSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\GoalGetterSusu\GoalGetterSusuAccountPauseCreateRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GoalGetterSusuAccountPauseCreateController extends Controller
{
    /**
     * @param Customer $customer
     * @param GoalGetterSusu $goalGetterSusu
     * @param GoalGetterSusuAccountPauseCreateRequest $goalGetterSusuAccountPauseCreateRequest
     * @param GoalGetterSusuAccountPauseCreateAction $goalGetterSusuAccountPauseCreateAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        GoalGetterSusu $goalGetterSusu,
        GoalGetterSusuAccountPauseCreateRequest $goalGetterSusuAccountPauseCreateRequest,
        GoalGetterSusuAccountPauseCreateAction $goalGetterSusuAccountPauseCreateAction
    ): JsonResponse {
        // Execute the GoalGetterSusuAccountPauseCreateAction and return the JsonResponse
        return $goalGetterSusuAccountPauseCreateAction->execute(
            goalGetterSusu: $goalGetterSusu,
            request: $goalGetterSusuAccountPauseCreateRequest->validated()
        );
    }
}
