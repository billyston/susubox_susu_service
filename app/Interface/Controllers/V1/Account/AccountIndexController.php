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
     * @throws SystemFailureException
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
