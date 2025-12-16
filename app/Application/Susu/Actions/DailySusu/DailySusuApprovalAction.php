<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\DailySusu;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Transaction\DTOs\RecurringDebitApprovalResponseDTO;
use App\Application\Transaction\ValueObject\RecurringDepositValueObject;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Services\PaymentInstructionCreateService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Domain\Transaction\Enums\TransactionCategoryCode;
use App\Domain\Transaction\Services\TransactionCategoryByCodeService;
use App\Interface\Resources\V1\Susu\IndividualSusu\DailySusu\DailySusuResource;
use App\Services\SusuBox\Http\Requests\RecurringDebitApprovalRequestHandler;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuApprovalAction
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
            initial_deposit: $dailySusu->initial_deposit,
            susu_amount: $dailySusu->susu_amount,
            start_date: $dailySusu->start_date,
            end_date: $dailySusu->end_date,
            frequency: $dailySusu->frequency->code,
            rollover_enabled: $dailySusu->rollover_enabled
        );

        // Execute the PaymentInstructionCreateService and return the payment instruction resource
        $paymentInstruction = $this->paymentInstructionCreateService->execute(
            transaction_category: $transactionCategory,
            account: $dailySusu->account,
            wallet: $dailySusu->wallet,
            customer: $customer,
            data: $debitValues->toArray()
        );

        // Build the RecurringDebitApprovalResponseDTO
        $response_dto = RecurringDebitApprovalResponseDTO::fromDomain(
            payment_instruction: $paymentInstruction,
        );

        // Dispatch to SusuBox Service (Payment Service)
        $this->dispatcher->sendToSusuBoxService(
            service: config('susubox.payment.name'),
            data: $response_dto->toArray(),
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
