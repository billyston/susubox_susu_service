<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\DailySusu\RecurringDepositPause;

use App\Application\PaymentInstruction\DTOs\RecurringDeposit\RecurringDepositPauseRequestDTO;
use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\PaymentInstruction\Services\RecurringDeposit\RecurringDepositPauseCreateService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Resources\V1\Account\AccountPauseResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuRecurringDepositPauseCreateAction
{
    private RecurringDepositPauseCreateService $recurringDepositPauseCreateService;

    /**
     * @param RecurringDepositPauseCreateService $recurringDepositPauseCreateService
     */
    public function __construct(
        RecurringDepositPauseCreateService $recurringDepositPauseCreateService,
    ) {
        $this->recurringDepositPauseCreateService = $recurringDepositPauseCreateService;
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
        // Extract the main resources
        $account = $dailySusu->account;
        $recurringDeposit = $account->recurringDeposit;

        // Build the RecurringDepositPauseRequestDTO
        $requestDTO = RecurringDepositPauseRequestDTO::fromPayload(
            payload: $request
        );

        // Execute the RecurringDepositPauseCreateService and return the resource
        $recurringDepositPause = $this->recurringDepositPauseCreateService->execute(
            recurringDeposit: $recurringDeposit,
            requestDTO: $requestDTO
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'The account pause was successfully created.',
            data: new AccountPauseResource(
                resource: $recurringDepositPause->refresh()
            )
        );
    }
}
