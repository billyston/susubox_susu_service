<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Account;

use App\Application\Account\Actions\Account\AccountBalanceAction;
use App\Domain\Account\Models\Account;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Interface\Controllers\Shared\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class AccountBalanceController extends Controller
{
    /**
     * @param Customer $customer
     * @param Account $account
     * @param Request $request
     * @param AccountBalanceAction $accountBalanceAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        Account $account,
        Request $request,
        AccountBalanceAction $accountBalanceAction
    ): JsonResponse {
        // Execute the AccountBalanceAction and return the JsonResponse
        return $accountBalanceAction->execute(
            account: $account,
        );
    }
}
