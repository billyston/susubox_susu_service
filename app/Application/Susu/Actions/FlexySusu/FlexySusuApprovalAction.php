<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\FlexySusu;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Transaction\DTOs\DirectDebitApprovalResponseDTO;
use App\Application\Transaction\ValueObject\DirectDepositInitialValueObject;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Services\PaymentInstructionCreateService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\FlexySusu;
use App\Domain\Transaction\Enums\TransactionCategoryCode;
use App\Domain\Transaction\Services\TransactionCategoryByCodeService;
use App\Interface\Resources\V1\Susu\IndividualSusu\FlexySusu\FlexySusuResource;
use App\Services\SusuBox\Http\Requests\DirectDebitApprovalRequestHandler;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class FlexySusuApprovalAction
{
    private PaymentInstructionCreateService $paymentInstructionCreateService;
    private TransactionCategoryByCodeService $transactionCategoryByCodeGetService;
    private DirectDebitApprovalRequestHandler $dispatcher;

    public function __construct(
        PaymentInstructionCreateService $paymentInstructionCreateService,
        TransactionCategoryByCodeService $transactionCategoryByCodeGetService,
        DirectDebitApprovalRequestHandler $dispatcher
    ) {
        $this->paymentInstructionCreateService = $paymentInstructionCreateService;
        $this->transactionCategoryByCodeGetService = $transactionCategoryByCodeGetService;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param Customer $customer
     * @param FlexySusu $flexySusu
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws MoneyMismatchException
     * @throws UnknownCurrencyException
     */
    public function execute(
        Customer $customer,
        FlexySusu $flexySusu,
    ): JsonResponse {
        // Execute the TransactionCreateService and return the Transaction resource
        $transactionCategory = $this->transactionCategoryByCodeGetService->execute(
            TransactionCategoryCode::DIRECT_DEBIT_CODE->value
        );

        // Build the PaymentInstructionCreateRequestVO
        $debitValues = DirectDepositInitialValueObject::create(
            initial_deposit: $flexySusu->initial_deposit,
        );

        // Execute the PaymentInstructionCreateService and return the payment instruction resource
        $paymentInstruction = $this->paymentInstructionCreateService->execute(
            transaction_category: $transactionCategory,
            account: $flexySusu->account,
            wallet: $flexySusu->wallet,
            customer: $customer,
            data: $debitValues->toArray()
        );

        // Build the DirectDebitApprovalResponseDTO
        $response_dto = DirectDebitApprovalResponseDTO::fromDomain(
            payment_instruction: $paymentInstruction,
            wallet: $paymentInstruction->wallet,
            product: $flexySusu,
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
            description: 'Your flexy susu account has been approved.',
            data: new FlexySusuResource(
                resource: $flexySusu->refresh()
            ),
        );
    }
}
