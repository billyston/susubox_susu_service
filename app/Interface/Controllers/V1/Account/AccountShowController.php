<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Account;

use App\Application\Account\Actions\Account\AccountShowAction;
use App\Domain\Account\Models\Account;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Interface\Controllers\Shared\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class AccountShowController extends Controller
{
    /**
     * @param Request $request
     * @param Customer $customer
     * @param Account $account
     * @param AccountShowAction $accountShowAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Request $request,
        Customer $customer,
        Account $account,
        AccountShowAction $accountShowAction
    ): JsonResponse {
        // Execute the AccountShowAction and return the JsonResponse
        return $accountShowAction->execute(
            customer: $customer,
            account: $account
        );
    }
}
