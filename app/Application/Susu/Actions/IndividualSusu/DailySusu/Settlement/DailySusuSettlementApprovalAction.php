<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\DailySusu\Settlement;

use App\Application\PaymentInstruction\DTOs\Settlement\SettlementApprovalResponseDTO;
use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\PaymentInstruction\Models\Settlement;
use App\Domain\PaymentInstruction\Services\PaymentInstruction\PaymentInstructionApprovalStatusUpdateService;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Resources\V1\PaymentInstruction\SettlementResource;
use App\Services\SusuBox\Http\SusuBoxServiceDispatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuSettlementApprovalAction
{
    private PaymentInstructionApprovalStatusUpdateService $paymentInstructionApprovalStatusUpdateService;
    private SusuBoxServiceDispatcher $susuBoxServiceDispatcher;

    /**
     * @param PaymentInstructionApprovalStatusUpdateService $paymentInstructionApprovalStatusUpdateService
     * @param SusuBoxServiceDispatcher $susuBoxServiceDispatcher
     */
    public function __construct(
        PaymentInstructionApprovalStatusUpdateService $paymentInstructionApprovalStatusUpdateService,
        SusuBoxServiceDispatcher $susuBoxServiceDispatcher,
    ) {
        $this->paymentInstructionApprovalStatusUpdateService = $paymentInstructionApprovalStatusUpdateService;
        $this->susuBoxServiceDispatcher = $susuBoxServiceDispatcher;
    }

    /**
     * @param DailySusu $dailySusu
     * @param Settlement $settlement
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function execute(
        DailySusu $dailySusu,
        Settlement $settlement,
    ): JsonResponse {
        // Execute the PaymentInstructionApprovalStatusUpdateService and return the resource
        $paymentInstruction = $this->paymentInstructionApprovalStatusUpdateService->execute(
            paymentInstruction: $settlement->paymentInstruction,
            status: Statuses::APPROVED->value,
        );

        // Build the DirectDepositApprovalResponseDTO
        $responseDTO = SettlementApprovalResponseDTO::fromDomain(
            paymentInstruction: $paymentInstruction,
            wallet: $paymentInstruction->wallet,
            product: $dailySusu,
        );

        // Dispatch to SusuBox Service (Payment Service)
        $this->susuBoxServiceDispatcher->send(
            service: config('susubox.payment.name'),
            endpoint: 'payouts',
            payload: $responseDTO->toArray(),
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'Your request is successful. You will be notified shortly.',
            data: new SettlementResource(
                resource: $settlement->refresh()
            )
        );
    }
}
