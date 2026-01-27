<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\GoalGetterSusu\Pause;

use App\Application\Susu\Actions\IndividualSusu\GoalGetterSusu\Pause\GoalGetterSusuPauseCreateAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\GoalGetterSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\GoalGetterSusu\Pause\GoalGetterSusuPauseCreateRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GoalGetterSusuAccountPauseCreateController extends Controller
{
    /**
     * @param Customer $customer
     * @param GoalGetterSusu $goalGetterSusu
     * @param GoalGetterSusuPauseCreateRequest $goalGetterSusuPauseCreateRequest
     * @param GoalGetterSusuPauseCreateAction $goalGetterSusuPauseCreateAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        GoalGetterSusu $goalGetterSusu,
        GoalGetterSusuPauseCreateRequest $goalGetterSusuPauseCreateRequest,
        GoalGetterSusuPauseCreateAction $goalGetterSusuPauseCreateAction
    ): JsonResponse {
        // Execute the GoalGetterSusuPauseCreateAction and return the JsonResponse
        return $goalGetterSusuPauseCreateAction->execute(
            goalGetterSusu: $goalGetterSusu,
            request: $goalGetterSusuPauseCreateRequest->validated()
        );
    }
}
