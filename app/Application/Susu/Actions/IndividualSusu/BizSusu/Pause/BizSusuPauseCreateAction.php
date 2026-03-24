<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\BizSusu\Pause;

use App\Application\PaymentInstruction\DTOs\RecurringDeposit\RecurringDepositPauseRequestDTO;
use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\PaymentInstruction\Services\RecurringDeposit\RecurringDepositPauseCreateService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Interface\Resources\V1\Account\AccountPauseResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class BizSusuPauseCreateAction
{
    private RecurringDepositPauseCreateService $accountPauseCreateService;

    public function __construct(
        RecurringDepositPauseCreateService $accountPauseCreateService
    ) {
        $this->accountPauseCreateService = $accountPauseCreateService;
    }

    /**
     * @param BizSusu $bizSusu
     * @param array $request
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function execute(
        BizSusu $bizSusu,
        array $request
    ): JsonResponse {
        // Build the RecurringDepositPauseRequestDTO
        $requestDTO = RecurringDepositPauseRequestDTO::fromPayload(
            payload: $request
        );

        // Execute the RecurringDepositPauseCreateService and return the resource
        $accountPause = $this->accountPauseCreateService->execute(
            susuAccount: $bizSusu,
            requestDTO: $requestDTO
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'The account pause was successfully created.',
            data: new AccountPauseResource(
                resource: $accountPause->refresh()
            )
        );
    }
}
