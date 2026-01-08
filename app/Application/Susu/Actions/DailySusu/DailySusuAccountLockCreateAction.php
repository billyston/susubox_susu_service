<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\DailySusu;

use App\Application\Account\DTOs\AccountLockRequestDTO;
use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Account\Services\AccountLockCreateService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Resources\V1\Account\AccountLockResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuAccountLockCreateAction
{
    private AccountLockCreateService $accountLockCreateService;

    public function __construct(
        AccountLockCreateService $accountLockCreateService
    ) {
        $this->accountLockCreateService = $accountLockCreateService;
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
        $requestDTO = AccountLockRequestDTO::fromPayload(
            payload: $request
        );

        // Execute the AccountLockCreateService and return the resource
        $accountLock = $this->accountLockCreateService->execute(
            susuAccount: $dailySusu,
            requestDTO: $requestDTO
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'The account lock was successfully created.',
            data: new AccountLockResource(
                resource: $accountLock->refresh()
            )
        );
    }
}
