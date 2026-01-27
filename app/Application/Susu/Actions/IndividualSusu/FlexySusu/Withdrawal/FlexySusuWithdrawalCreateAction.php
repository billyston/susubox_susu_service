<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\FlexySusu\Withdrawal;

use App\Application\Account\Services\AccountBalanceGuardService;
use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Transaction\ValueObject\WithdrawalValueObject;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Services\PaymentInstructionCreateService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\FlexySusu;
use App\Domain\Transaction\Enums\TransactionCategoryCode;
use App\Domain\Transaction\Exceptions\InsufficientBalanceException;
use App\Domain\Transaction\Services\TransactionCategoryByCodeService;
use App\Interface\Resources\V1\PaymentInstruction\WithdrawalResource;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class FlexySusuWithdrawalCreateAction
{
    private TransactionCategoryByCodeService $transactionCategoryByCodeGetService;
    private AccountBalanceGuardService $accountBalanceGuardService;
    private PaymentInstructionCreateService $paymentInstructionCreateService;

    /**
     * @param TransactionCategoryByCodeService $transactionCategoryByCodeGetService
     * @param AccountBalanceGuardService $accountBalanceGuardService
     * @param PaymentInstructionCreateService $paymentInstructionCreateService
     */
    public function __construct(
        TransactionCategoryByCodeService $transactionCategoryByCodeGetService,
        AccountBalanceGuardService $accountBalanceGuardService,
        PaymentInstructionCreateService $paymentInstructionCreateService
    ) {
        $this->transactionCategoryByCodeGetService = $transactionCategoryByCodeGetService;
        $this->accountBalanceGuardService = $accountBalanceGuardService;
        $this->paymentInstructionCreateService = $paymentInstructionCreateService;
    }

    /**
     * @param Customer $customer
     * @param FlexySusu $flexySusu
     * @param array $request
     * @return JsonResponse
     * @throws MoneyMismatchException
     * @throws UnknownCurrencyException
     * @throws SystemFailureException
     * @throws InsufficientBalanceException
     */
    public function execute(
        Customer $customer,
        FlexySusu $flexySusu,
        array $request,
    ): JsonResponse {
        // Build the WithdrawalCreateRequestDTO
        $requestDTO = WithdrawalValueObject::create(
            payload: $request,
            availableBalance: $flexySusu->account->accountBalance->available_balance,
        );

        // Execute the AccountBalanceGuardService
        $this->accountBalanceGuardService->execute(
            availableBalance: $flexySusu->account->accountBalance->available_balance,
            debitAmount: $requestDTO->amount
        );

        // Execute the TransactionCreateDebitService and return the resource
        $transactionCategory = $this->transactionCategoryByCodeGetService->execute(
            TransactionCategoryCode::WITHDRAWAL_CODE->value
        );

        // Execute the PaymentInstructionCreateService and return the payment instruction resource
        $paymentInstruction = $this->paymentInstructionCreateService->execute(
            transactionCategory: $transactionCategory,
            account: $flexySusu->account,
            wallet: $flexySusu->wallet,
            customer: $customer,
            data: $requestDTO->toArray()
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'The withdrawal process has been initiated successfully.',
            data: new WithdrawalResource(
                resource: $paymentInstruction->refresh()
            )
        );
    }
}
