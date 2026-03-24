<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\FlexySusu\Lock;

use App\Application\Account\DTOs\AccountPayoutLock\AccountPayoutLockRequestDTO;
use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Account\Services\AccountPayoutLock\AccountPayoutLockCreateService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\FlexySusu;
use App\Interface\Resources\V1\Account\AccountPayoutLock\AccountPayoutLockResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class FlexySusuLockCreateAction
{
    private AccountPayoutLockCreateService $accountLockCreateService;

    public function __construct(
        AccountPayoutLockCreateService $accountLockCreateService
    ) {
        $this->accountLockCreateService = $accountLockCreateService;
    }

    /**
     * @param FlexySusu $flexySusu
     * @param array $request
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function execute(
        FlexySusu $flexySusu,
        array $request
    ): JsonResponse {
        // Build the accountLockRequestDTO
        $requestDTO = AccountPayoutLockRequestDTO::fromPayload(
            payload: $request
        );

        // Execute the AccountPayoutLockCreateService and return the resource
        $accountLock = $this->accountLockCreateService->execute(
            susuAccount: $flexySusu,
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
