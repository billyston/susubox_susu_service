<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\GoalGetterSusu;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Transaction\DTOs\RecurringDebitApprovalResponseDTO;
use App\Application\Transaction\ValueObject\RecurringDepositValueObject;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Services\PaymentInstructionCreateService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\GoalGetterSusu;
use App\Domain\Transaction\Enums\TransactionCategoryCode;
use App\Domain\Transaction\Services\TransactionCategoryByCodeService;
use App\Interface\Resources\V1\Susu\GoalGetterSusu\GoalGetterSusuResource;
use App\Services\SusuBox\Http\Requests\RecurringDebitApprovalRequestHandler;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class GoalGetterSusuApprovalAction
{
    private PaymentInstructionCreateService $paymentInstructionCreateService;
    private TransactionCategoryByCodeService $transactionCategoryByCodeGetService;
    private RecurringDebitApprovalRequestHandler $dispatcher;

    public function __construct(
        PaymentInstructionCreateService $paymentInstructionCreateService,
        TransactionCategoryByCodeService $transactionCategoryByCodeGetService,
        RecurringDebitApprovalRequestHandler $dispatcher
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
        // Execute the TransactionCreateService and return the Transaction resource
        $transactionCategory = $this->transactionCategoryByCodeGetService->execute(
            TransactionCategoryCode::RECURRING_DEBIT_CODE->value
        );

        // Build the RecurringDepositValueObject
        $value_object = RecurringDepositValueObject::create(
            initial_deposit: $goalGetterSusu->initial_deposit,
            susu_amount: $goalGetterSusu->susu_amount,
            charge: null,
            start_date: $goalGetterSusu->start_date,
            end_date: $goalGetterSusu->end_date,
            frequency: $goalGetterSusu->frequency->code,
            rollover_enabled: $goalGetterSusu->rollover_enabled
        );

        // Execute the PaymentInstructionCreateService and return the payment instruction resource
        $payment_instruction = $this->paymentInstructionCreateService->execute(
            transaction_category: $transactionCategory,
            account: $goalGetterSusu->account,
            wallet: $goalGetterSusu->wallet,
            customer: $customer,
            data: $value_object->toArray()
        );

        // Build the RecurringDebitApprovalResponseDTO
        $response_dto = RecurringDebitApprovalResponseDTO::fromDomain(
            payment_instruction: $payment_instruction,
        );

        // Execute the RecurringDebitApprovalRequestHandler
        $this->dispatcher->sendToService(
            service: config('susubox.payment.name'),
            data: $response_dto->toArray(),
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
