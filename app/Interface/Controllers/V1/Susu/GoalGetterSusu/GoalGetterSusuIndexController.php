<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\GoalGetterSusu;

use App\Application\Susu\Actions\GoalGetterSusu\GoalGetterSusuIndexAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SusuSchemeNotFoundException;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Interface\Controllers\Shared\Controller;
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
