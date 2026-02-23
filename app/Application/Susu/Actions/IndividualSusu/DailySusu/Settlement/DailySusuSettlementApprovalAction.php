<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\DailySusu\Settlement;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Transaction\DTOs\SettlementApprovalResponseDTO;
use App\Domain\PaymentInstruction\Models\Settlement;
use App\Domain\PaymentInstruction\Services\PaymentInstructionApprovalStatusUpdateService;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Resources\V1\Susu\IndividualSusu\DailySusu\DailySusuSettlementResource;
use App\Services\SusuBox\Http\Requests\Payment\PaymentRequestHandler;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuSettlementApprovalAction
{
    private PaymentInstructionApprovalStatusUpdateService $paymentInstructionApprovalStatusUpdateService;
    private PaymentRequestHandler $dispatcher;

    public function __construct(
        PaymentInstructionApprovalStatusUpdateService $paymentInstructionApprovalStatusUpdateService,
        PaymentRequestHandler $dispatcher,
    ) {
        $this->paymentInstructionApprovalStatusUpdateService = $paymentInstructionApprovalStatusUpdateService;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param DailySusu $dailySusu
     * @param Settlement $accountSettlement
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnknownCurrencyException
     */
    public function execute(
        DailySusu $dailySusu,
        Settlement $accountSettlement,
    ): JsonResponse {
        // Execute the PaymentInstructionApprovalStatusUpdateService and return the resource
        $paymentInstruction = $this->paymentInstructionApprovalStatusUpdateService->execute(
            paymentInstruction: $accountSettlement->paymentInstruction,
            status: Statuses::APPROVED->value,
        );

        // Build the DirectDepositApprovalResponseDTO
        $responseDTO = SettlementApprovalResponseDTO::fromDomain(
            paymentInstruction: $paymentInstruction,
            wallet: $paymentInstruction->wallet,
            product: $dailySusu,
        );

        // Dispatch to SusuBox Service (Payment Service)
        $this->dispatcher->sendToSusuBoxService(
            service: config('susubox.payment.name'),
            endpoint: 'payouts',
            data: $responseDTO->toArray(),
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'Your request is successful. You will be notified shortly.',
            data: new DailySusuSettlementResource(
                resource: $accountSettlement->refresh()
            )
        );
    }
}
