<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\GoalGetterSusu;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Transaction\DTOs\RecurringDepositApprovalResponseDTO;
use App\Application\Transaction\ValueObject\RecurringDepositValueObject;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Services\PaymentInstructionCreateService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\GoalGetterSusu;
use App\Domain\Transaction\Enums\TransactionCategoryCode;
use App\Domain\Transaction\Services\TransactionCategoryByCodeService;
use App\Interface\Resources\V1\Susu\IndividualSusu\GoalGetterSusu\GoalGetterSusuResource;
use App\Services\SusuBox\Http\Requests\RecurringDepositApprovalRequestHandler;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class GoalGetterSusuApprovalAction
{
    private PaymentInstructionCreateService $paymentInstructionCreateService;
    private TransactionCategoryByCodeService $transactionCategoryByCodeGetService;
    private RecurringDepositApprovalRequestHandler $dispatcher;

    /**
     * @param PaymentInstructionCreateService $paymentInstructionCreateService
     * @param TransactionCategoryByCodeService $transactionCategoryByCodeGetService
     * @param RecurringDepositApprovalRequestHandler $dispatcher
     */
    public function __construct(
        PaymentInstructionCreateService $paymentInstructionCreateService,
        TransactionCategoryByCodeService $transactionCategoryByCodeGetService,
        RecurringDepositApprovalRequestHandler $dispatcher
    ) {
        $this->paymentInstructionCreateService = $paymentInstructionCreateService;
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

        // Build the RecurringDepositApprovalResponseDTO
        $responseDTO = RecurringDepositApprovalResponseDTO::fromDomain(
            paymentInstruction: $paymentInstruction,
        );

        // Dispatch to SusuBox Service (Payment Service)
        $this->dispatcher->sendToSusuBoxService(
            service: config('susubox.payment.name'),
            data: $responseDTO->toArray(),
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
