<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\GoalGetterSusu;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Transaction\ValueObject\DirectDepositValueObject;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Services\PaymentInstructionCreateService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\GoalGetterSusu;
use App\Domain\Transaction\Enums\TransactionCategoryCode;
use App\Domain\Transaction\Services\TransactionCategoryByCodeService;
use App\Interface\Resources\V1\PaymentInstruction\DirectDepositResource;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class GoalGetterSusuDirectDepositCreateAction
{
    private TransactionCategoryByCodeService $transactionCategoryByCodeGetService;
    private PaymentInstructionCreateService $paymentInstructionCreateService;

    public function __construct(
        TransactionCategoryByCodeService $transactionCategoryByCodeGetService,
        PaymentInstructionCreateService $paymentInstructionCreateService
    ) {
        $this->transactionCategoryByCodeGetService = $transactionCategoryByCodeGetService;
        $this->paymentInstructionCreateService = $paymentInstructionCreateService;
    }

    /**
     * @param Customer $customer
     * @param GoalGetterSusu $goalGetterSusu
     * @param array $request
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnknownCurrencyException
     * @throws MoneyMismatchException
     */
    public function execute(
        Customer $customer,
        GoalGetterSusu $goalGetterSusu,
        array $request,
    ): JsonResponse {
        // Build the DirectDepositCreateRequestDTO
        $requestDto = DirectDepositValueObject::create(
            payload: $request,
            susuAmount: $goalGetterSusu->susu_amount
        );

        // Execute the TransactionCreateService and return the resource
        $transactionCategory = $this->transactionCategoryByCodeGetService->execute(
            TransactionCategoryCode::DIRECT_DEBIT_CODE->value
        );

        // Execute the PaymentInstructionCreateService and return the payment instruction resource
        $paymentInstruction = $this->paymentInstructionCreateService->execute(
            transaction_category: $transactionCategory,
            account: $goalGetterSusu->account,
            wallet: $goalGetterSusu->wallet,
            customer: $customer,
            data: $requestDto->toArray()
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'The direct deposit was successfully created.',
            data: new DirectDepositResource(
                resource: $paymentInstruction->refresh()
            )
        );
    }
}
