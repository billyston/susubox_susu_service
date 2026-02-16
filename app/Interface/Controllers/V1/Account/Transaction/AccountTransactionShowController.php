<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Account\Transaction;

use App\Application\Account\Actions\Transaction\AccountTransactionShowAction;
use App\Domain\Account\Models\Account;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Transaction\Models\Transaction;
use App\Interface\Controllers\Shared\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class AccountTransactionShowController extends Controller
{
    /**
     * @param Request $request
     * @param Customer $customer
     * @param Account $account
     * @param Transaction $transaction
     * @param AccountTransactionShowAction $accountTransactionShowAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Request $request,
        Customer $customer,
        Account $account,
        Transaction $transaction,
        AccountTransactionShowAction $accountTransactionShowAction
    ): JsonResponse {
        // Execute the AccountTransactionShowAction and return the JsonResponse
        return $accountTransactionShowAction->execute(
            customer: $customer,
            account: $account,
            transaction: $transaction,
        );
    }
}
