<?php

declare(strict_types=1);

namespace App\Application\Account\Actions\AccountTransaction;

use App\Application\Account\DTOs\AccountTransaction\AccountTransactionStatisticsResponseDTO;
use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Transaction\Services\Statistics\AccountTransactionStatsService;
use App\Domain\Account\Models\Account;
use Brick\Money\Exception\MoneyMismatchException;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class AccountTransactionStatisticsAction
{
    private AccountTransactionStatsService $accountTransactionStatsService;

    /**
     * @param AccountTransactionStatsService $accountTransactionStatsService
     */
    public function __construct(
        AccountTransactionStatsService $accountTransactionStatsService,
    ) {
        $this->accountTransactionStatsService = $accountTransactionStatsService;
    }

    /**
     * @param array $request
     * @param Account $account
     * @return JsonResponse
     * @throws MoneyMismatchException
     */
    public function execute(
        array $request,
        Account $account,
    ): JsonResponse {
        // Execute the AccountAutoDebitService
        $statistics = $this->accountTransactionStatsService->execute(
            account: $account,
            from: isset($request['from_date']) ? Carbon::parse($request['from_date']) : null,
            to: isset($request['to_date']) ? Carbon::parse($request['to_date']) : null,
        );

        // Build the AccountLockResponseDTO
        $responseDTO = AccountTransactionStatisticsResponseDTO::fromDomain(
            statistics: $statistics,
            request: $request,
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::toArray(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            data: $responseDTO->toArray(),
        );
    }
}
