<?php

declare(strict_types=1);

namespace App\Application\Account\Actions;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Account\Models\Account;
use App\Domain\Account\Services\AccountBalanceService;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Interface\Resources\V1\Account\AccountBalanceResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class AccountBalanceAction
{
    private AccountBalanceService $accountBalanceService;

    public function __construct(
        AccountBalanceService $accountBalanceService
    ) {
        $this->accountBalanceService = $accountBalanceService;
    }

    /**
     * @throws SystemFailureException
     */
    public function execute(
        Customer $customer,
        Account $account,
        array $request,
    ): JsonResponse {
        // Execute the AccountIndexService and return the collection
        $accountBalance = $this->accountBalanceService->execute(
            account: $account,
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            data: new AccountBalanceResource(
                resource: $accountBalance
            ),
        );
    }
}
