<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\GoalGetterSusu\Account;

use App\Application\Susu\Actions\IndividualSusu\GoalGetterSusu\Account\GoalGetterSusuCreateAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\GoalGetterSusu\Account\GoalGetterSusuCreateRequest;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GoalGetterSusuCreateController extends Controller
{
    /**
     * @param Customer $customer
     * @param GoalGetterSusuCreateRequest $goalGetterSusuCreateRequest
     * @param GoalGetterSusuCreateAction $goalGetterSusuCreateAction
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnknownCurrencyException
     */
    public function __invoke(
        Customer $customer,
        GoalGetterSusuCreateRequest $goalGetterSusuCreateRequest,
        GoalGetterSusuCreateAction $goalGetterSusuCreateAction
    ): JsonResponse {
        // Execute the GoalGetterSusuCreateAction and return the GoalGetterSusu resource
        return $goalGetterSusuCreateAction->execute(
            customer: $customer,
            request: $goalGetterSusuCreateRequest->validated()
        );
    }
}
