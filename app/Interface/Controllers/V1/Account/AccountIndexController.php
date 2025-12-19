<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Account;

use App\Application\Account\Actions\AccountIndexAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Interface\Controllers\Shared\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class AccountIndexController extends Controller
{
    /**
     * @param Request $request
     * @param Customer $customer
     * @param AccountIndexAction $accountIndexAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Request $request,
        Customer $customer,
        AccountIndexAction $accountIndexAction
    ): JsonResponse {
        // Execute the AccountIndexAction and return the JsonResponse
        return $accountIndexAction->execute(
            customer: $customer,
            request: $request
        );
    }
}
