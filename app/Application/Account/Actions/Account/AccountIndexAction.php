<?php

declare(strict_types=1);

namespace App\Application\Account\Actions\Account;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Shared\Helpers\Helpers;
use App\Application\Shared\Helpers\Relationships;
use App\Domain\Account\Services\Account\AccountIndexService;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Interface\Resources\V1\Account\AccountResource;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class AccountIndexAction
{
    use Relationships;

    private AccountIndexService $accountIndexService;

    /**
     * @param AccountIndexService $accountIndexService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(
        AccountIndexService $accountIndexService
    ) {
        $this->accountIndexService = $accountIndexService;
        $this->relationships = Helpers::includeResources();
    }

    /**
     * @param Customer $customer
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function execute(
        Customer $customer,
    ): JsonResponse {
        // Execute the AccountIndexService and return the collection
        $accounts = $this->accountIndexService->execute(
            customer: $customer,
        );

        // (Guard) Load related resources if exist
        if ($this->loadRelationships()) {
            $accounts->load($this->relationships);
        }

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            data: AccountResource::collection(
                resource: $accounts
            ),
        );
    }
}
