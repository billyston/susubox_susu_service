<?php

declare(strict_types=1);

namespace App\Application\Account\Actions\Transaction;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Shared\Helpers\Helpers;
use App\Application\Shared\Helpers\Relationships;
use App\Domain\Account\Models\Account;
use App\Domain\Account\Services\AccountTransaction\AccountTransactionShowService;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Transaction\Models\Transaction;
use App\Interface\Resources\V1\Account\AccountTransaction\AccountTransactionResource;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class AccountTransactionShowAction
{
    use Relationships;

    private AccountTransactionShowService $accountTransactionShowService;

    /**
     * @param AccountTransactionShowService $accountTransactionShowService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(
        AccountTransactionShowService $accountTransactionShowService
    ) {
        $this->accountTransactionShowService = $accountTransactionShowService;
        $this->relationships = Helpers::includeResources();
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
        // Execute the AccountTransactionShowService, and return the collection
        $transaction = $this->accountTransactionShowService->execute(
            customer: $customer,
            account: $account,
            transaction: $transaction
        );

        // (Guard) Load related resources if exist
        if ($this->loadRelationships()) {
            $transaction->load($this->relationships);
        }

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
