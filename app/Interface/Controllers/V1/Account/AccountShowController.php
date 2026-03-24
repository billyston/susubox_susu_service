<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Account;

use App\Application\Account\Actions\Account\AccountShowAction;
use App\Domain\Account\Models\Account;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Interface\Controllers\Shared\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

final class AccountShowController extends Controller
{
    /**
     * @param Customer $customer
     * @param Account $account
     * @param AccountShowAction $accountShowAction
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function __invoke(
        Customer $customer,
        Account $account,
        AccountShowAction $accountShowAction
    ): JsonResponse {
        // Execute the AccountShowAction and return the JsonResponse
        return $accountShowAction->execute(
            customer: $customer,
            account: $account,
        );
    }
}
