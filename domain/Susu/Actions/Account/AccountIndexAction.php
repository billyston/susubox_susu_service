<?php

declare(strict_types=1);

namespace Domain\Susu\Actions\Account;

use App\Common\Helpers\ApiResponseBuilder;
use App\Exceptions\Common\SystemFailureException;
use Domain\Customer\Models\Customer;
use Domain\Shared\Exceptions\UnauthorisedAccessException;
use Domain\Susu\Data\Account\AccountResource;
use Domain\Susu\Services\Account\AccountIndexService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class AccountIndexAction
{
    private AccountIndexService $accountIndexService;

    public function __construct(
        AccountIndexService $accountIndexService
    ) {
        $this->accountIndexService = $accountIndexService;
    }

    /**
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function execute(
        Customer $customer,
        Request $request,
    ): JsonResponse {
        // Execute the AccountIndexService and return the collection
        $customer_accounts = $this->accountIndexService->execute(
            customer: $customer,
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            data: AccountResource::collection(
                resource: $customer_accounts
            ),
        );
    }
}
