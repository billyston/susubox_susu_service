<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\GoalGetterSusu\Account;

use App\Application\PaymentInstruction\DTOs\RecurringDeposit\RecurringDepositCreatedResponseDTO;
use App\Application\PaymentInstruction\ValueObject\RecurringDeposit\RecurringDepositValueObject;
use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Services\PaymentInstruction\PaymentInstructionApprovalStatusUpdateService;
use App\Domain\PaymentInstruction\Services\PaymentInstruction\PaymentInstructionCreateService;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\GoalGetterSusu;
use App\Domain\Transaction\Enums\TransactionCategoryCode;
use App\Domain\Transaction\Services\TransactionCategoryByCodeService;
use App\Interface\Resources\V1\Susu\IndividualSusu\GoalGetterSusu\GoalGetterSusuResource;
use App\Services\SusuBox\Http\Requests\Payment\PaymentServiceRequestDispatcher;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class GoalGetterSusuApprovalAction
{
    private PaymentInstructionCreateService $paymentInstructionCreateService;
    private PaymentInstructionApprovalStatusUpdateService $paymentInstructionApprovalStatusUpdateService;
    private TransactionCategoryByCodeService $transactionCategoryByCodeGetService;
    private PaymentServiceRequestDispatcher $dispatcher;

    /**
     * @param PaymentInstructionCreateService $paymentInstructionCreateService
     * @param PaymentInstructionApprovalStatusUpdateService $paymentInstructionApprovalStatusUpdateService
     * @param TransactionCategoryByCodeService $transactionCategoryByCodeGetService
     * @param PaymentServiceRequestDispatcher $dispatcher
     */
    public function __construct(
        PaymentInstructionCreateService $paymentInstructionCreateService,
        PaymentInstructionApprovalStatusUpdateService $paymentInstructionApprovalStatusUpdateService,
        TransactionCategoryByCodeService $transactionCategoryByCodeGetService,
        PaymentServiceRequestDispatcher $dispatcher
    ) {
        $this->paymentInstructionCreateService = $paymentInstructionCreateService;
        $this->paymentInstructionApprovalStatusUpdateService = $paymentInstructionApprovalStatusUpdateService;
        $this->transactionCategoryByCodeGetService = $transactionCategoryByCodeGetService;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param Customer $customer
     * @param GoalGetterSusu $goalGetterSusu
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws MoneyMismatchException
     * @throws UnknownCurrencyException
     */
    public function execute(
        Customer $customer,
        GoalGetterSusu $goalGetterSusu,
    ): JsonResponse {
        // Execute the TransactionCreateDebitService and return the Transaction resource
        $transactionCategory = $this->transactionCategoryByCodeGetService->execute(
            TransactionCategoryCode::RECURRING_DEBIT_CODE->value
        );

        // Build the RecurringDepositValueObject
        $debitValues = RecurringDepositValueObject::create(
            initialDeposit: $goalGetterSusu->initial_deposit,
            susuAmount: $goalGetterSusu->susu_amount,
            startDate: $goalGetterSusu->start_date,
            endDate: $goalGetterSusu->end_date,
            frequency: $goalGetterSusu->frequency->code,
            rolloverEnabled: $goalGetterSusu->rollover_enabled
        );

        // Execute the PaymentInstructionCreateService and return the payment instruction resource
        $paymentInstruction = $this->paymentInstructionCreateService->execute(
            transactionCategory: $transactionCategory,
            account: $goalGetterSusu->account,
            wallet: $goalGetterSusu->wallet,
            customer: $customer,
            data: $debitValues->toArray()
        );

        // Build the RecurringDepositCreatedResponseDTO
        $responseDTO = RecurringDepositCreatedResponseDTO::fromDomain(
            paymentInstruction: $paymentInstruction,
        );

        // Dispatch to SusuBox Service (Payment Service)
        $this->dispatcher->sendToSusuBoxService(
            service: config('susubox.payment.name'),
            endpoint: 'recurring-debits',
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
            description: 'Your goal getter susu account has been approved.',
            data: new GoalGetterSusuResource(
                resource: $goalGetterSusu->refresh()
            ),
        );
    }
}
