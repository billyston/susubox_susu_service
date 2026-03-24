<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\DailySusu\DirectDeposit;

use App\Application\PaymentInstruction\ValueObject\DirectDeposit\DirectDepositValueObject;
use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Services\PaymentInstruction\PaymentInstructionCreateService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Domain\Transaction\Enums\TransactionCategoryCode;
use App\Domain\Transaction\Enums\TransactionType;
use App\Domain\Transaction\Services\TransactionCategoryByCodeService;
use App\Interface\Resources\V1\PaymentInstruction\DirectDepositResource;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuDirectDepositCreateAction
{
    private TransactionCategoryByCodeService $transactionCategoryByCodeGetService;
    private PaymentInstructionCreateService $paymentInstructionCreateService;

    /**
     * @param TransactionCategoryByCodeService $transactionCategoryByCodeGetService
     * @param PaymentInstructionCreateService $paymentInstructionCreateService
     */
    public function __construct(
        TransactionCategoryByCodeService $transactionCategoryByCodeGetService,
        PaymentInstructionCreateService $paymentInstructionCreateService
    ) {
        $this->transactionCategoryByCodeGetService = $transactionCategoryByCodeGetService;
        $this->paymentInstructionCreateService = $paymentInstructionCreateService;
    }

    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param array $request
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnknownCurrencyException
     * @throws MoneyMismatchException
     */
    public function execute(
        Customer $customer,
        DailySusu $dailySusu,
        array $request
    ): JsonResponse {
        // Extract the main resources
        $account = $dailySusu->account;
        $accountCustomer = $account->accountCustomer;
        $recurringDeposit = $account->recurringDeposit;

        // Build the DirectDepositCreateRequestDTO
        $requestDTO = DirectDepositValueObject::create(
            payload: $request,
            recurringAmount: $recurringDeposit->recurring_amount
        );

        // Execute the TransactionCreateDebitService and return the resource
        $transactionCategory = $this->transactionCategoryByCodeGetService->execute(
            TransactionCategoryCode::DIRECT_DEBIT_CODE->value
        );

        // Execute the PaymentInstructionCreateService and return the payment instruction resource
        $paymentInstruction = $this->paymentInstructionCreateService->execute(
            account: $account,
            transactionCategory: $transactionCategory,
            accountCustomer: $accountCustomer,
            transactionType: TransactionType::CREDIT,
            wallet: $accountCustomer->wallet,
            amount: $requestDTO->amount,
            charge: $requestDTO->charge,
            total: $requestDTO->total,
            acceptedTerms: $requestDTO->toArray()['accepted_terms'],
            metadata: $requestDTO->toArray()['metadata']
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
