<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\DailySusu\DirectDeposit;

use App\Application\PaymentInstruction\DTOs\DirectDeposit\DirectDepositApprovalResponseDTO;
use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\PaymentInstruction\Services\PaymentInstruction\PaymentInstructionApprovalStatusUpdateService;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Resources\V1\PaymentInstruction\DirectDepositResource;
use App\Services\SusuBox\Http\SusuBoxServiceDispatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuDirectDepositApprovalAction
{
    private PaymentInstructionApprovalStatusUpdateService $paymentInstructionApprovalStatusUpdateService;
    private SusuBoxServiceDispatcher $susuBoxServiceDispatcher;

    /**
     * @param PaymentInstructionApprovalStatusUpdateService $paymentInstructionApprovalStatusUpdateService
     * @param SusuBoxServiceDispatcher $susuBoxServiceDispatcher
     */
    public function __construct(
        PaymentInstructionApprovalStatusUpdateService $paymentInstructionApprovalStatusUpdateService,
        SusuBoxServiceDispatcher $susuBoxServiceDispatcher
    ) {
        $this->paymentInstructionApprovalStatusUpdateService = $paymentInstructionApprovalStatusUpdateService;
        $this->susuBoxServiceDispatcher = $susuBoxServiceDispatcher;
    }

    /**
     * @param DailySusu $dailySusu
     * @param PaymentInstruction $paymentInstruction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function execute(
        DailySusu $dailySusu,
        PaymentInstruction $paymentInstruction,
    ): JsonResponse {
        // Build the DirectDepositApprovalResponseDTO
        $responseDTO = DirectDepositApprovalResponseDTO::fromDomain(
            paymentInstruction: $paymentInstruction,
            wallet: $paymentInstruction->wallet,
            product: $dailySusu,
        );

        // Dispatch to SusuBoxService (Payment Service)
        $this->susuBoxServiceDispatcher->send(
            service: config('susubox.payment.name'),
            endpoint: 'direct-debits',
            payload: $responseDTO->toArray(),
        );

        // Execute the PaymentInstructionApprovalStatusUpdateService and return the resource
        $paymentInstruction = $this->paymentInstructionApprovalStatusUpdateService->execute(
            paymentInstruction: $paymentInstruction,
            status: Statuses::APPROVED->value,
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'Your request is successful. You will be notified shortly.',
            data: new DirectDepositResource(
                resource: $paymentInstruction->refresh()
            )
        );
    }
}
