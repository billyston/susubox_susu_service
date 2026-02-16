<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Account\Transaction;

use App\Application\Account\Actions\Transaction\AccountTransactionStatisticsAction;
use App\Domain\Account\Models\Account;
use App\Domain\Customer\Models\Customer;
use App\Interface\Controllers\Shared\Controller;
use Brick\Money\Exception\MoneyMismatchException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class AccountTransactionStatisticsController extends Controller
{
    /**
     * @param Request $request
     * @param Customer $customer
     * @param Account $account
     * @param AccountTransactionStatisticsAction $accountTransactionStatisticsAction
     * @return JsonResponse
     * @throws MoneyMismatchException
     */
    public function __invoke(
        Request $request,
        Customer $customer,
        Account $account,
        AccountTransactionStatisticsAction $accountTransactionStatisticsAction
    ): JsonResponse {
        // Execute the AccountTransactionStatisticsAction and return the JsonResponse
        return $accountTransactionStatisticsAction->execute(
            request: $request->all(),
            account: $account,
        );
    }
}
