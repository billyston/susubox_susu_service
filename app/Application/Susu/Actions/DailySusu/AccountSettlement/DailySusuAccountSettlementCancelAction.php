<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\DailySusu\AccountSettlement;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Account\Models\AccountSettlement;
use App\Domain\Account\Services\AccountAutoDebit\AccountSettlementStatusUpdateService;
use App\Domain\PaymentInstruction\Services\PaymentInstructionCancelService;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuAccountSettlementCancelAction
{
    private AccountSettlementStatusUpdateService $accountSettlementStatusUpdateService;
    private PaymentInstructionCancelService $paymentInstructionCancelService;

    /**
     * @param AccountSettlementStatusUpdateService $accountSettlementStatusUpdateService
     * @param PaymentInstructionCancelService $paymentInstructionCancelService
     */
    public function __construct(
        AccountSettlementStatusUpdateService $accountSettlementStatusUpdateService,
        PaymentInstructionCancelService $paymentInstructionCancelService
    ) {
        $this->accountSettlementStatusUpdateService = $accountSettlementStatusUpdateService;
        $this->paymentInstructionCancelService = $paymentInstructionCancelService;
    }

    /**
     * @param AccountSettlement $accountSettlement
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function execute(
        AccountSettlement $accountSettlement,
    ): JsonResponse {
        // Execute the PaymentInstructionCancelService and return the resource
        $this->accountSettlementStatusUpdateService->execute(
            accountSettlement: $accountSettlement,
            status: Statuses::CANCELLED->value
        );

        // Execute the PaymentInstructionCancelService and return the resource
        $this->paymentInstructionCancelService->execute(
            paymentInstruction: $accountSettlement->payment,
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'The account settlement process has been canceled successfully.',
        );
    }
}
