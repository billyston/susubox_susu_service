<?php

declare(strict_types=1);

namespace App\Application\Account\Actions\Transaction;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Account\Models\Account;
use App\Domain\Account\Services\AccountTransaction\AccountIndividualTransactionIndexService;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Interface\Resources\V1\Account\AccountTransaction\AccountTransactionCollectionResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class AccountTransactionIndexAction
{
    private AccountIndividualTransactionIndexService $accountTransactionIndexService;

    /**
     * @param AccountIndividualTransactionIndexService $accountTransactionIndexService
     */
    public function __construct(
        AccountIndividualTransactionIndexService $accountTransactionIndexService
    ) {
        $this->accountTransactionIndexService = $accountTransactionIndexService;
    }

    /**
     * @param Customer $customer
     * @param Account $account
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function execute(
        Customer $customer,
        Account $account,
    ): JsonResponse {
        // Execute the AccountIndividualTransactionIndexService and return the collection
        $accountTransactions = $this->accountTransactionIndexService->execute(
            customer: $customer,
            account: $account
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::paginated(
            status: true,
            code: Response::HTTP_OK,
            message: 'Request successful.',
            data: AccountTransactionCollectionResource::collection(
                resource: $accountTransactions,
            ),
        );
    }
}
