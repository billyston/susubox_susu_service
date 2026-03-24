<?php

declare(strict_types=1);

namespace App\Application\PaymentInstruction\Actions\RecurringDeposit;

use App\Application\PaymentInstruction\DTOs\RecurringDeposit\RecurringDepositRequestDTO;
use App\Application\PaymentInstruction\Jobs\RecurringDeposit\RecurringDepositRolloverJob;
use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\PaymentInstruction\Models\RecurringDeposit;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class RecurringDepositRolloverAction
{
    /**
     * @param RecurringDeposit $recurringDeposit
     * @param array $request
     * @return JsonResponse
     * @throws UnknownCurrencyException
     */
    public function execute(
        RecurringDeposit $recurringDeposit,
        array $request,
    ): JsonResponse {
        // Build the RecurringDepositRequestDTO
        $requestDTO = RecurringDepositRequestDTO::fromPayload(
            payload: $request
        );

        // Dispatch the RecurringDepositRolloverJob
        RecurringDepositRolloverJob::dispatch(
            recurringDepositResourceID: $recurringDeposit->resource_id,
            requestDTO: $requestDTO
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_NO_CONTENT,
            message: 'Request accepted',
            description: 'The request was accepted for processing',
        );
    }
}
