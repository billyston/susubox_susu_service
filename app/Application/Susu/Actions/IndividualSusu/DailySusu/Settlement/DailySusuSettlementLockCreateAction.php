<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\DailySusu\Settlement;

use App\Application\Account\DTOs\AccountPayoutLock\AccountPayoutLockRequestDTO;
use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Account\Services\AccountPayoutLock\AccountPayoutLockCreateService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Resources\V1\Account\AccountPayoutLock\AccountPayoutLockResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuSettlementLockCreateAction
{
    private AccountPayoutLockCreateService $accountPayoutLockCreateService;

    /**
     * @param AccountPayoutLockCreateService $accountPayoutLockCreateService
     */
    public function __construct(
        AccountPayoutLockCreateService $accountPayoutLockCreateService
    ) {
        $this->accountPayoutLockCreateService = $accountPayoutLockCreateService;
    }

    /**
     * @param DailySusu $dailySusu
     * @param array $request
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function execute(
        DailySusu $dailySusu,
        array $request
    ): JsonResponse {
        // Build the accountLockRequestDTO
        $requestDTO = AccountPayoutLockRequestDTO::fromPayload(
            payload: $request
        );

        // Execute the AccountPayoutLockCreateService and return the resource
        $accountLock = $this->accountPayoutLockCreateService->execute(
            account: $dailySusu->account,
            requestDTO: $requestDTO
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'The account lock was successfully created.',
            data: new AccountPayoutLockResource(
                resource: $accountLock->refresh()
            )
        );
    }
}
