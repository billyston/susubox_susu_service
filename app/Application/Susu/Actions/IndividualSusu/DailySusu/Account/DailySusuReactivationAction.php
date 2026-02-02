<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\DailySusu\Account;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Transaction\DTOs\RecurringDeposit\RecurringDepositApprovalResponseDTO;
use App\Domain\PaymentInstruction\Services\PaymentInstructionApprovalStatusUpdateService;
use App\Domain\PaymentInstruction\Services\PaymentInstructionFailedRecurringDepositService;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Resources\V1\Susu\IndividualSusu\DailySusu\DailySusuResource;
use App\Services\SusuBox\Http\Requests\Payment\PaymentRequestHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuReactivationAction
{
    private PaymentInstructionFailedRecurringDepositService $paymentInstructionFailedRecurringDepositService;
    private PaymentInstructionApprovalStatusUpdateService $paymentInstructionApprovalStatusUpdateService;
    private PaymentRequestHandler $dispatcher;

    /**
     * @param PaymentInstructionFailedRecurringDepositService $paymentInstructionFailedRecurringDepositService
     * @param PaymentInstructionApprovalStatusUpdateService $paymentInstructionApprovalStatusUpdateService
     * @param PaymentRequestHandler $dispatcher
     */
    public function __construct(
        PaymentInstructionFailedRecurringDepositService $paymentInstructionFailedRecurringDepositService,
        PaymentInstructionApprovalStatusUpdateService $paymentInstructionApprovalStatusUpdateService,
        PaymentRequestHandler $dispatcher,
    ) {
        $this->paymentInstructionFailedRecurringDepositService = $paymentInstructionFailedRecurringDepositService;
        $this->paymentInstructionApprovalStatusUpdateService = $paymentInstructionApprovalStatusUpdateService;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param DailySusu $dailySusu
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function execute(
        DailySusu $dailySusu,
    ): JsonResponse {
        // Execute the PaymentInstructionFailedRecurringDepositService
        $paymentInstruction = $this->paymentInstructionFailedRecurringDepositService->execute(
            account: $dailySusu->individual->account
        );

        // Build the RecurringDepositApprovalResponseDTO
        $responseDTO = RecurringDepositApprovalResponseDTO::fromDomain(
            paymentInstruction: $paymentInstruction,
        );

        // Dispatch to SusuBox Service (Payment Service)
        $this->dispatcher->sendToSusuBoxService(
            service: config('susubox.payment.name'),
            endpoint: 'recurring-debits/reactivation',
            data: $responseDTO->toArray(),
        );

        // Execute the PaymentInstructionApprovalStatusUpdateService
        $this->paymentInstructionApprovalStatusUpdateService->execute(
            paymentInstruction: $paymentInstruction,
            status: Statuses::APPROVED->value,
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'Your daily susu account has been approved.',
            data: new DailySusuResource(
                resource: $dailySusu->refresh()
            ),
        );
    }
}
