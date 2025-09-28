<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Susu\Account;

use App\Exceptions\Common\SystemFailureException;
use App\Http\Controllers\Controller;
use Domain\Customer\Models\Customer;
use Domain\Shared\Exceptions\UnauthorisedAccessException;
use Domain\Susu\Actions\Account\AccountIndexAction;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class AccountIndexController extends Controller
{
    /**
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function __invoke(
        Customer $customer,
        Request $request,
        AccountIndexAction $accountIndexAction
    ): JsonResponse {
        // Execute the AccountIndexAction and return the JsonResponse
        return $accountIndexAction->execute(
            customer: $customer,
            request: $request
        );
    }
}
