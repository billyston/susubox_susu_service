<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Account\AccountTransaction;

use App\Application\Account\Actions\AccountTransaction\AccountTransactionIndexAction;
use App\Domain\Account\Models\Account;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Interface\Controllers\Shared\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class AccountTransactionIndexController extends Controller
{
    /**
     * @param Request $request
     * @param Customer $customer
     * @param Account $account
     * @param AccountTransactionIndexAction $accountTransactionIndexAction
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function __invoke(
        Request $request,
        Customer $customer,
        Account $account,
        AccountTransactionIndexAction $accountTransactionIndexAction
    ): JsonResponse {
        // Execute the AccountTransactionIndexAction and return the JsonResponse
        return $accountTransactionIndexAction->execute(
            customer: $customer,
            account: $account
        );
    }
}
