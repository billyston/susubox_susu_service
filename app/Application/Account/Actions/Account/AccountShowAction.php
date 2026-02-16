<?php

declare(strict_types=1);

namespace App\Application\Account\Actions\Account;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Account\Models\Account;
use App\Domain\Account\Services\Account\AccountShowService;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Interface\Resources\V1\Account\AccountResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class AccountShowAction
{
    private AccountShowService $accountShowService;

    /**
     * @param AccountShowService $accountShowService
     */
    public function __construct(
        AccountShowService $accountShowService
    ) {
        $this->accountShowService = $accountShowService;
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
        // Execute the AccountIndexService and return the collection
        $account = $this->accountShowService->execute(
            customer: $customer,
            account: $account
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            data: new AccountResource(
                resource: $account
            ),
        );
    }
}
