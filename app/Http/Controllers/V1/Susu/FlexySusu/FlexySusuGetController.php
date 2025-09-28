<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Susu\FlexySusu;

use App\Exceptions\Common\SystemFailureException;
use App\Http\Controllers\Controller;
use Domain\Customer\Models\Customer;
use Domain\Shared\Exceptions\UnauthorisedAccessException;
use Domain\Susu\Actions\FlexySusu\FlexySusuGetAction;
use Domain\Susu\Models\Account;
use Symfony\Component\HttpFoundation\JsonResponse;

final class FlexySusuGetController extends Controller
{
    /**
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function __invoke(
        Customer $customer,
        Account $account,
        FlexySusuGetAction $flexySusuGetAction
    ): JsonResponse {
        // Execute the FlexySusuGetAction and return the JsonResponse
        return $flexySusuGetAction->execute(
            customer: $customer,
            account: $account,
        );
    }
}
