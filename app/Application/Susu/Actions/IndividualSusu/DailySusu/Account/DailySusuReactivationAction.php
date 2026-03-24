<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\DailySusu\Account;

use App\Application\PaymentInstruction\DTOs\RecurringDeposit\RecurringDepositReactivationResponseDTO;
use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\PaymentInstruction\Services\PaymentInstruction\PaymentInstructionApprovalStatusUpdateService;
use App\Domain\PaymentInstruction\Services\RecurringDeposit\RecurringDepositGetService;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Resources\V1\Susu\IndividualSusu\DailySusu\DailySusuResource;
use App\Services\SusuBox\Http\SusuBoxServiceDispatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuReactivationAction
{
    private RecurringDepositGetService $recurringDepositGetService;
    private PaymentInstructionApprovalStatusUpdateService $paymentInstructionApprovalStatusUpdateService;
    private SusuBoxServiceDispatcher $susuBoxServiceDispatcher;

    /**
     * @param RecurringDepositGetService $paymentInstructionRecurringDepositGetService
     * @param PaymentInstructionApprovalStatusUpdateService $paymentInstructionApprovalStatusUpdateService
     * @param SusuBoxServiceDispatcher $susuBoxServiceDispatcher
     */
    public function __construct(
        RecurringDepositGetService $paymentInstructionRecurringDepositGetService,
        PaymentInstructionApprovalStatusUpdateService $paymentInstructionApprovalStatusUpdateService,
        SusuBoxServiceDispatcher $susuBoxServiceDispatcher
    ) {
        $this->recurringDepositGetService = $paymentInstructionRecurringDepositGetService;
        $this->paymentInstructionApprovalStatusUpdateService = $paymentInstructionApprovalStatusUpdateService;
        $this->susuBoxServiceDispatcher = $susuBoxServiceDispatcher;
    }

    /**
     * @param DailySusu $dailySusu
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function execute(
        DailySusu $dailySusu,
    ): JsonResponse {
        // Extract the key resource
        $account = $dailySusu->account;
        $accountCustomer = $account->accountCustomer;

        // Execute the RecurringDepositGetService
        $recurringDeposit = $this->recurringDepositGetService->execute(
            account: $dailySusu->account,
            accountCustomer: $accountCustomer,
            status: Statuses::FAILED->value,
        );

        // Build the RecurringDepositReactivationResponseDTO
        $responseDTO = RecurringDepositReactivationResponseDTO::fromDomain(
            recurringDeposit: $recurringDeposit,
        );

        // Dispatch to SusuBox Service (Payment Service)
        $this->susuBoxServiceDispatcher->send(
            service: config('susubox.payment.name'),
            endpoint: 'recurring-debits/'.$recurringDeposit->resource_id.'/reactivation',
            payload: $responseDTO->toArray(),
        );

        // Execute the PaymentInstructionApprovalStatusUpdateService
        $this->paymentInstructionApprovalStatusUpdateService->execute(
            paymentInstruction: $recurringDeposit->paymentInstruction,
            status: Statuses::APPROVED->value,
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'Your daily susu account has been approved.',
            data: new DailySusuResource(
                resource: $dailySusu
            ),
        );
    }
}
