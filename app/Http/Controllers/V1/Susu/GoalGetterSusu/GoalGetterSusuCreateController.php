<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Susu\GoalGetterSusu;

use App\Exceptions\Common\SystemFailureException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Susu\GoalGetterSusu\GoalGetterSusuCreateRequest;
use Domain\Customer\Models\Customer;
use Domain\Susu\Actions\GoalGetterSusu\GoalGetterSusuCreateAction;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GoalGetterSusuCreateController extends Controller
{
    /**
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        GoalGetterSusuCreateRequest $goalGetterSusuCreateRequest,
        GoalGetterSusuCreateAction $goalGetterSusuCreateAction
    ): JsonResponse {
        // Execute the GoalGetterSusuCreateAction and return the JsonResponse
        return $goalGetterSusuCreateAction->execute(
            customer: $customer,
            goalGetterSusuCreateRequest: $goalGetterSusuCreateRequest
        );
    }
}
