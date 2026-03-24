<?php

declare(strict_types=1);

namespace App\Application\Account\Actions\Transaction;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Shared\Helpers\Helpers;
use App\Application\Shared\Helpers\Relationships;
use App\Domain\Account\Models\Account;
use App\Domain\Account\Services\AccountTransaction\AccountTransactionIndexService;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Interface\Resources\V1\Account\AccountTransaction\AccountTransactionResource;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class AccountTransactionIndexAction
{
    use Relationships;

    private AccountTransactionIndexService $accountTransactionIndexService;

    /**
     * @param AccountTransactionIndexService $accountTransactionIndexService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(
        AccountTransactionIndexService $accountTransactionIndexService
    ) {
        $this->accountTransactionIndexService = $accountTransactionIndexService;
        $this->relationships = Helpers::includeResources();
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
        // Execute the AccountTransactionIndexService and return the collection
        $transactions = $this->accountTransactionIndexService->execute(
            customer: $customer,
            account: $account
        );

        // (Guard) Load related resources if exist
        if ($this->loadRelationships()) {
            $transactions->load($this->relationships);
        }

        // Build and return the JsonResponse
        return ApiResponseBuilder::paginated(
            status: true,
            code: Response::HTTP_OK,
            message: 'Request successful.',
            data: AccountTransactionResource::collection(
                resource: $transactions,
            ),
        );
    }
}
