<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Susu\GoalGetterSusu;

use App\Exceptions\Common\SystemFailureException;
use App\Http\Controllers\Controller;
use Domain\Customer\Models\Customer;
use Domain\Shared\Exceptions\SusuSchemeNotFoundException;
use Domain\Shared\Exceptions\UnauthorisedAccessException;
use Domain\Susu\Actions\GoalGetterSusu\GoalGetterSusuIndexAction;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GoalGetterSusuIndexController extends Controller
{
    /**
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     * @throws SusuSchemeNotFoundException
     */
    public function __invoke(
        Customer $customer,
        GoalGetterSusuIndexAction $goalGetterSusuIndexAction
    ): JsonResponse {
        // Execute the GoalGetterSusuIndexAction and return the JsonResponse
        return $goalGetterSusuIndexAction->execute(
            customer: $customer,
        );
    }
}
