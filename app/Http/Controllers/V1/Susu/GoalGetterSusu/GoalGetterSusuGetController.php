<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Susu\GoalGetterSusu;

use App\Exceptions\Common\SystemFailureException;
use App\Http\Controllers\Controller;
use Domain\Customer\Models\Customer;
use Domain\Shared\Exceptions\UnauthorisedAccessException;
use Domain\Susu\Actions\GoalGetterSusu\GoalGetterSusuGetAction;
use Domain\Susu\Models\Account;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GoalGetterSusuGetController extends Controller
{
    /**
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function __invoke(
        Customer $customer,
        Account $account,
        GoalGetterSusuGetAction $goalGetterSusuGetAction
    ): JsonResponse {
        // Execute the GoalGetterGetAction and return the JsonResponse
        return $goalGetterSusuGetAction->execute(
            customer: $customer,
            account: $account,
        );
    }
}
