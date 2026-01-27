<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\DailySusu\Pause;

use App\Application\Account\DTOs\AccountPauseRequestDTO;
use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Account\Services\AccountPause\AccountPauseCreateService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Resources\V1\Account\AccountPauseResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuPauseCreateAction
{
    private AccountPauseCreateService $accountPauseCreateService;

    public function __construct(
        AccountPauseCreateService $accountPauseCreateService
    ) {
        $this->accountPauseCreateService = $accountPauseCreateService;
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
        // Build the AccountPauseRequestDTO
        $requestDTO = AccountPauseRequestDTO::fromPayload(
            payload: $request
        );

        // Execute the AccountPauseCreateService and return the resource
        $AccountPause = $this->accountPauseCreateService->execute(
            susuAccount: $dailySusu,
            requestDTO: $requestDTO
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'The account pause was successfully created.',
            data: new AccountPauseResource(
                resource: $AccountPause->refresh()
            )
        );
    }
}
