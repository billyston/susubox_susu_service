<?php

declare(strict_types=1);

namespace App\Application\PaymentInstruction\Actions\RecurringDeposit;

use App\Application\PaymentInstruction\DTOs\RecurringDeposit\RecurringDepositPausedRequestDTO;
use App\Application\PaymentInstruction\Jobs\RecurringDeposit\RecurringDepositResumedJob;
use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\PaymentInstruction\Models\RecurringDepositPause;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class RecurringDepositResumeAction
{
    /**
     * @param RecurringDepositPause $recurringDepositPause
     * @param array $request
     * @return JsonResponse
     * @throws UnknownCurrencyException
     */
    public function execute(
        RecurringDepositPause $recurringDepositPause,
        array $request,
    ): JsonResponse {
        // Build the RecurringDepositPausedRequestDTO
        $requestDTO = RecurringDepositPausedRequestDTO::fromPayload(
            payload: $request
        );

        // Dispatch the RecurringDepositPausedJob
        RecurringDepositResumedJob::dispatch(
            recurringDepositPauseResourceID: $recurringDepositPause->resource_id,
            requestDTO: $requestDTO,
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_NO_CONTENT,
            message: 'Request accepted',
            description: 'The request was accepted for processing',
        );
    }
}
