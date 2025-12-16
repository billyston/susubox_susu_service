<?php

declare(strict_types=1);

namespace App\Application\Transaction\Actions;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Transaction\DTOs\TransactionCreateRequestDTO;
use App\Application\Transaction\Jobs\TransactionCreateJob;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class TransactionCreateAction
{
    /**
     * @param PaymentInstruction $paymentInstruction
     * @param array $request
     * @return JsonResponse
     * @throws UnknownCurrencyException
     */
    public function execute(
        PaymentInstruction $paymentInstruction,
        array $request,
    ): JsonResponse {
        // Build the TransactionCreateRequestDTO and return the DTO
        $requestDto = TransactionCreateRequestDTO::fromArray(
            payload: $request,
            isInitialDeposit: $paymentInstruction->account->isFirstTransaction()
        );

        // Dispatch the TransactionCreateJob
        TransactionCreateJob::dispatch(
            paymentInstructionResourceId: $paymentInstruction->resource_id,
            requestDto: $requestDto
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_ACCEPTED,
            message: 'Request accepted',
            description: 'The request was accepted for processing',
        );
    }
}
