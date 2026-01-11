<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\DailySusu;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Transaction\DTOs\RecurringDepositApprovalResponseDTO;
use App\Application\Transaction\ValueObject\RecurringDepositValueObject;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Services\PaymentInstructionApprovalStatusUpdateService;
use App\Domain\PaymentInstruction\Services\PaymentInstructionCreateService;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Domain\Transaction\Enums\TransactionCategoryCode;
use App\Domain\Transaction\Services\TransactionCategoryByCodeService;
use App\Interface\Resources\V1\Susu\IndividualSusu\DailySusu\DailySusuResource;
use App\Services\SusuBox\Http\Requests\Payment\RecurringDepositApprovalRequestHandler;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuApprovalAction
{
    private TransactionCategoryByCodeService $transactionCategoryByCodeGetService;
    private PaymentInstructionCreateService $paymentInstructionCreateService;
    private PaymentInstructionApprovalStatusUpdateService $paymentInstructionApprovalStatusUpdateService;
    private RecurringDepositApprovalRequestHandler $dispatcher;

    /**
     * @param PaymentInstructionCreateService $paymentInstructionCreateService
     * @param PaymentInstructionApprovalStatusUpdateService $paymentInstructionApprovalStatusUpdateService
     * @param RecurringDepositApprovalRequestHandler $dispatcher
     * @param TransactionCategoryByCodeService $transactionCategoryByCodeGetService
     */
    public function __construct(
        PaymentInstructionCreateService $paymentInstructionCreateService,
        PaymentInstructionApprovalStatusUpdateService $paymentInstructionApprovalStatusUpdateService,
        TransactionCategoryByCodeService $transactionCategoryByCodeGetService,
        RecurringDepositApprovalRequestHandler $dispatcher,
    ) {
        $this->paymentInstructionCreateService = $paymentInstructionCreateService;
        $this->paymentInstructionApprovalStatusUpdateService = $paymentInstructionApprovalStatusUpdateService;
        $this->transactionCategoryByCodeGetService = $transactionCategoryByCodeGetService;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws MoneyMismatchException
     * @throws UnknownCurrencyException
     */
    public function execute(
        Customer $customer,
        DailySusu $dailySusu,
    ): JsonResponse {
        // Execute the TransactionCreateDebitService and return the Transaction resource
        $transactionCategory = $this->transactionCategoryByCodeGetService->execute(
            TransactionCategoryCode::RECURRING_DEBIT_CODE->value
        );

        // Build the RecurringDepositValueObject
        $debitValues = RecurringDepositValueObject::create(
            initialDepositFrequency: $dailySusu->initial_deposit_frequency,
            initialDeposit: $dailySusu->initial_deposit,
            susuAmount: $dailySusu->susu_amount,
            startDate: $dailySusu->start_date,
            endDate: $dailySusu->end_date,
            frequency: $dailySusu->frequency->code,
            rolloverEnabled: $dailySusu->rollover_enabled
        );

        // Execute the PaymentInstructionCreateService and return the payment instruction resource
        $paymentInstruction = $this->paymentInstructionCreateService->execute(
            transactionCategory: $transactionCategory,
            account: $dailySusu->account,
            wallet: $dailySusu->wallet,
            customer: $customer,
            data: $debitValues->toArray()
        );

        // Build the RecurringDepositApprovalResponseDTO
        $responseDTO = RecurringDepositApprovalResponseDTO::fromDomain(
            paymentInstruction: $paymentInstruction,
        );

        // Dispatch to SusuBox Service (Payment Service)
        $this->dispatcher->sendToSusuBoxService(
            service: config('susubox.payment.name'),
            data: $responseDTO->toArray(),
        );

        // Execute the PaymentInstructionCreateService
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
