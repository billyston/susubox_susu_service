<?php

declare(strict_types=1);

namespace App\Application\Account\Actions;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Account\Services\AccountIndexService;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Interface\Resources\V1\Account\AccountResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class AccountIndexAction
{
    private AccountIndexService $accountIndexService;

    /**
     * @param AccountIndexService $accountIndexService
     */
    public function __construct(
        AccountIndexService $accountIndexService
    ) {
        $this->accountIndexService = $accountIndexService;
    }

    /**
     * @param Customer $customer
     * @param Request $request
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function execute(
        Customer $customer,
        Request $request,
    ): JsonResponse {
        // Execute the AccountIndexService and return the collection
        $customerAccounts = $this->accountIndexService->execute(
            customer: $customer,
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            data: AccountResource::collection(
                resource: $customerAccounts
            ),
        );
    }
}
