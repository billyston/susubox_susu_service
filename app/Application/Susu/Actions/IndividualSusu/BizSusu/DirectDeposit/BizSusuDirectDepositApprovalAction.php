<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\BizSusu\DirectDeposit;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Transaction\DTOs\DirectDepositApprovalResponseDTO;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\PaymentInstruction\Services\PaymentInstructionApprovalStatusUpdateService;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Interface\Resources\V1\PaymentInstruction\DirectDepositResource;
use App\Services\SusuBox\Http\Requests\Payment\PaymentRequestHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class BizSusuDirectDepositApprovalAction
{
    private PaymentInstructionApprovalStatusUpdateService $paymentInstructionApprovalStatusUpdateService;
    private PaymentRequestHandler $dispatcher;

    /**
     * @param PaymentInstructionApprovalStatusUpdateService $paymentInstructionApprovalStatusUpdateService
     * @param PaymentRequestHandler $dispatcher
     */
    public function __construct(
        PaymentInstructionApprovalStatusUpdateService $paymentInstructionApprovalStatusUpdateService,
        PaymentRequestHandler $dispatcher
    ) {
        $this->paymentInstructionApprovalStatusUpdateService = $paymentInstructionApprovalStatusUpdateService;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param BizSusu $bizSusu
     * @param PaymentInstruction $paymentInstruction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function execute(
        BizSusu $bizSusu,
        PaymentInstruction $paymentInstruction,
    ): JsonResponse {
        // Execute the PaymentInstructionApprovalStatusUpdateService and return the resource
        $paymentInstruction = $this->paymentInstructionApprovalStatusUpdateService->execute(
            paymentInstruction: $paymentInstruction,
            status: Statuses::APPROVED->value,
        );

        // Build the DirectDepositApprovalResponseDTO
        $responseDto = DirectDepositApprovalResponseDTO::fromDomain(
            paymentInstruction: $paymentInstruction,
            wallet: $paymentInstruction->wallet,
            product: $bizSusu,
        );

        // Dispatch to SusuBox Service (Payment Service)
        $this->dispatcher->sendToSusuBoxService(
            service: config('susubox.payment.name'),
            endpoint: 'direct-debits',
            data: $responseDto->toArray(),
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
