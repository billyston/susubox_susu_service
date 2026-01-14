<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\DailySusu\AccountSettlement;

use App\Application\Account\Services\AccountBalanceGuardService;
use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Susu\DTOs\DailySusu\AccountSettlement\DailySusuAccountSettlementRequestDTO;
use App\Application\Susu\Services\DailySusu\DailySusuSettlementCalculationService;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Services\PaymentInstructionCreateService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Domain\Susu\Services\IndividualSusu\DailySusu\AccountCycle\DailySusuAccountCycleSelectionService;
use App\Domain\Susu\Services\IndividualSusu\DailySusu\AccountSettlement\DailySusuAccountSettlementCreateService;
use App\Domain\Transaction\Enums\TransactionCategoryCode;
use App\Domain\Transaction\Services\TransactionCategoryByCodeService;
use App\Interface\Resources\V1\Susu\IndividualSusu\DailySusu\DailySusuSettlementResource;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class DailySusuAccountSettlementCreateAction
{
    private DailySusuAccountCycleSelectionService $dailySusuAccountCycleSelectionService;
    private DailySusuSettlementCalculationService $dailySusuSettlementCalculationService;
    private AccountBalanceGuardService $balanceValidationService;
    private TransactionCategoryByCodeService $transactionCategoryByCodeGetService;
    private PaymentInstructionCreateService $paymentInstructionCreateService;
    private DailySusuAccountSettlementCreateService $dailySusuSettlementService;

    /**
     * @param DailySusuAccountCycleSelectionService $dailySusuAccountCycleSelectionService
     * @param DailySusuSettlementCalculationService $dailySusuSettlementCalculationService
     * @param AccountBalanceGuardService $balanceValidationService
     * @param TransactionCategoryByCodeService $transactionCategoryByCodeGetService
     * @param PaymentInstructionCreateService $paymentInstructionCreateService
     * @param DailySusuAccountSettlementCreateService $dailySusuAccountSettlementCreateService
     */
    public function __construct(
        DailySusuAccountCycleSelectionService $dailySusuAccountCycleSelectionService,
        DailySusuSettlementCalculationService $dailySusuSettlementCalculationService,
        AccountBalanceGuardService $balanceValidationService,
        TransactionCategoryByCodeService $transactionCategoryByCodeGetService,
        PaymentInstructionCreateService $paymentInstructionCreateService,
        DailySusuAccountSettlementCreateService $dailySusuAccountSettlementCreateService,
    ) {
        $this->dailySusuAccountCycleSelectionService = $dailySusuAccountCycleSelectionService;
        $this->dailySusuSettlementCalculationService = $dailySusuSettlementCalculationService;
        $this->balanceValidationService = $balanceValidationService;
        $this->transactionCategoryByCodeGetService = $transactionCategoryByCodeGetService;
        $this->paymentInstructionCreateService = $paymentInstructionCreateService;
        $this->dailySusuSettlementService = $dailySusuAccountSettlementCreateService;
    }

    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param array $request
     * @return JsonResponse
     * @throws MoneyMismatchException
     * @throws SystemFailureException
     * @throws UnknownCurrencyException
     * @throws Throwable
     */
    public function execute(
        Customer $customer,
        DailySusu $dailySusu,
        array $request
    ): JsonResponse {
        // Build the accountLockRequestDTO
        $requestDTO = DailySusuAccountSettlementRequestDTO::fromPayload(
            payload: $request
        );

        // Execute the DailySusuAccountCycleSelectionService
        $accountCycles = $this->dailySusuAccountCycleSelectionService->execute(
            account: $dailySusu->individual->account,
            scope:$requestDTO->scope,
            cycleResourceIDs: $requestDTO->cycleResourceIDs
        );

        // Execute the DailySusuAccountCycleSelectionService
        $settlementCalculations = $this->dailySusuSettlementCalculationService->execute(
            uniteCharge: $dailySusu->susu_amount,
            accountCycles: $accountCycles,
            requestDTO: $requestDTO
        );

        // Execute the AccountBalanceGuardService
        $this->balanceValidationService->execute(
            availableBalance: $dailySusu->account->accountBalance->available_balance,
            debitAmount: $settlementCalculations->principal
        );

        // Execute the TransactionCreateDebitService and return the resource
        $transactionCategory = $this->transactionCategoryByCodeGetService->execute(
            TransactionCategoryCode::SETTLEMENT_CODE->value
        );

        // Execute the PaymentInstructionCreateService and return the payment instruction resource
        $paymentInstruction = $this->paymentInstructionCreateService->execute(
            transactionCategory: $transactionCategory,
            account: $dailySusu->account,
            wallet: $dailySusu->wallet,
            customer: $customer,
            data: $settlementCalculations->toArray()
        );

        // SettlementService should be executed here
        $settlement = $this->dailySusuSettlementService->execute(
            account: $dailySusu->account,
            accountCycles: $accountCycles,
            paymentInstruction: $paymentInstruction,
            requestVO: $settlementCalculations
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'The account settlement was successfully created.',
            data: new DailySusuSettlementResource(
                resource: $settlement
            )
        );
    }
}
