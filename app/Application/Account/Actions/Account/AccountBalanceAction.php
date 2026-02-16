<?php

declare(strict_types=1);

namespace App\Application\Account\Actions\Account;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Account\Models\Account;
use App\Domain\Account\Services\Account\AccountBalanceService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Interface\Resources\V1\Account\AccountBalanceResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class AccountBalanceAction
{
    private AccountBalanceService $accountBalanceService;

    /**
     * @param AccountBalanceService $accountBalanceService
     */
    public function __construct(
        AccountBalanceService $accountBalanceService
    ) {
        $this->accountBalanceService = $accountBalanceService;
    }

    /**
     * @param Account $account
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function execute(
        Account $account,
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
