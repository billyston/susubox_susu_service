<?php

declare(strict_types=1);

namespace App\Application\Transaction\Actions;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Transaction\DTOs\TransactionCreateRequestDTO;
use App\Application\Transaction\Jobs\TransactionJob;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class TransactionCreateAction
{
    /**
     * @param PaymentInstruction $paymentInstruction
     * @param array $request
     * @return JsonResponse
     */
    public function execute(
        PaymentInstruction $paymentInstruction,
        array $request,
    ): JsonResponse {
        // Build the TransactionCreateRequestDTO and return the DTO
        $requestDTO = TransactionCreateRequestDTO::fromPayload(
            payload: $request,
            paymentInstruction: $paymentInstruction,
        );

        // Dispatch the TransactionCreateJob
        TransactionJob::dispatch(
            paymentInstructionResourceId: $paymentInstruction->resource_id,
            requestDTO: $requestDTO
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_ACCEPTED,
            message: 'Request accepted',
            description: 'The request was accepted for processing',
        );
    }
}
