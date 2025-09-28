<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Susu\FlexySusu;

use App\Exceptions\Common\SystemFailureException;
use App\Http\Controllers\Controller;
use Domain\Customer\Models\Customer;
use Domain\Shared\Exceptions\UnauthorisedAccessException;
use Domain\Susu\Actions\FlexySusu\FlexySusuShowAction;
use Domain\Susu\Models\Account;
use Symfony\Component\HttpFoundation\JsonResponse;

final class FlexySusuShowController extends Controller
{
    /**
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function __invoke(
        Customer $customer,
        Account $account,
        FlexySusuShowAction $flexySusuShowAction
    ): JsonResponse {
        // Execute the FlexySusuShowAction and return the JsonResponse
        return $flexySusuShowAction->execute(
            customer: $customer,
            account: $account,
        );
    }
}
