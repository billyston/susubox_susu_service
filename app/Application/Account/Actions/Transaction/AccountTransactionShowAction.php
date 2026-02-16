<?php

declare(strict_types=1);

namespace App\Application\Account\Actions\Transaction;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Account\Models\Account;
use App\Domain\Account\Services\AccountTransaction\AccountIndividualTransactionShowService;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Transaction\Models\Transaction;
use App\Interface\Resources\V1\Account\AccountTransaction\AccountTransactionResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class AccountTransactionShowAction
{
    private AccountIndividualTransactionShowService $accountTransactionShowService;

    /**
     * @param AccountIndividualTransactionShowService $accountTransactionShowService
     */
    public function __construct(
        AccountIndividualTransactionShowService $accountTransactionShowService
    ) {
        $this->accountTransactionShowService = $accountTransactionShowService;
    }

    /**
     * @param Customer $customer
     * @param Account $account
     * @param Transaction $transaction
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function execute(
        Customer $customer,
        Account $account,
        Transaction $transaction,
    ): JsonResponse {
        // Execute the AccountIndividualTransactionShowService, and return the collection
        $transaction = $this->accountTransactionShowService->execute(
            customer: $customer,
            account: $account,
            transaction: $transaction
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            data: new AccountTransactionResource(
                resource: $transaction
            ),
        );
    }
}
