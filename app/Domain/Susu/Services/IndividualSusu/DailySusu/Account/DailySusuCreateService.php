<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\IndividualSusu\DailySusu\Account;

use App\Application\Susu\DTOs\IndividualSusu\DailySusu\Account\DailySusuCreateRequestDTO;
use App\Domain\Account\Enums\AccountType;
use App\Domain\Account\Services\Account\AccountCreateService;
use App\Domain\Account\Services\AccountBalance\AccountBalanceCreateService;
use App\Domain\Account\Services\AccountCustomer\AccountCustomerCreateService;
use App\Domain\Account\Services\AccountCycle\AccountCycleDefinitionCreateService;
use App\Domain\Customer\Enums\CustomerType;
use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Models\Wallet;
use App\Domain\PaymentInstruction\Services\PaymentInstruction\PaymentInstructionCreateService;
use App\Domain\PaymentInstruction\Services\RecurringDeposit\RecurringDepositCreateService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Models\Frequency;
use App\Domain\Shared\Models\SusuScheme;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Domain\Transaction\Enums\TransactionType;
use App\Domain\Transaction\Models\TransactionCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class DailySusuCreateService
{
    private AccountCreateService $accountCreateService;
    private AccountCustomerCreateService $accountCustomerCreateService;
    private AccountBalanceCreateService $accountBalanceCreateService;
    private AccountCycleDefinitionCreateService $accountCycleDefinitionCreateService;
    private PaymentInstructionCreateService $paymentInstructionCreateService;
    private RecurringDepositCreateService $recurringDepositCreateService;

    /**
     * @param AccountCreateService $accountCreateService
     * @param AccountCustomerCreateService $accountCustomerCreateService
     * @param AccountBalanceCreateService $accountBalanceCreateService
     * @param AccountCycleDefinitionCreateService $accountCycleDefinitionCreateService
     * @param PaymentInstructionCreateService $paymentInstructionCreateService
     * @param RecurringDepositCreateService $recurringDepositCreateService
     */
    public function __construct(
        AccountCreateService $accountCreateService,
        AccountCustomerCreateService $accountCustomerCreateService,
        AccountBalanceCreateService $accountBalanceCreateService,
        AccountCycleDefinitionCreateService $accountCycleDefinitionCreateService,
        PaymentInstructionCreateService $paymentInstructionCreateService,
        RecurringDepositCreateService $recurringDepositCreateService
    ) {
        $this->accountCreateService = $accountCreateService;
        $this->accountCustomerCreateService = $accountCustomerCreateService;
        $this->accountBalanceCreateService = $accountBalanceCreateService;
        $this->accountCycleDefinitionCreateService = $accountCycleDefinitionCreateService;
        $this->paymentInstructionCreateService = $paymentInstructionCreateService;
        $this->recurringDepositCreateService = $recurringDepositCreateService;
    }

    /**
     * @param Customer $customer
     * @param Wallet $wallet
     * @param DailySusuCreateRequestDTO $requestDTO
     * @param SusuScheme $susuScheme
     * @param Frequency $frequency
     * @param TransactionCategory $transactionCategory
     * @return DailySusu
     * @throws SystemFailureException
     */
    public function execute(
        Customer $customer,
        Wallet $wallet,
        DailySusuCreateRequestDTO $requestDTO,
        SusuScheme $susuScheme,
        Frequency $frequency,
        TransactionCategory $transactionCategory,
    ): DailySusu {
        try {
            // Execute the database transaction
            return DB::transaction(function () use (
                $customer,
                $wallet,
                $requestDTO,
                $susuScheme,
                $frequency,
                $transactionCategory,
            ) {
                // Execute the AccountCreateService and return the resource
                $account = $this->accountCreateService->execute(
                    susuScheme: $susuScheme,
                    accountName: $requestDTO->accountName,
                    accountType: AccountType::INDIVIDUAL,
                    acceptedTerms: $requestDTO->acceptedTerms
                );

                // Execute the AccountCustomerCreateService and return the resource
                $accountCustomer = $this->accountCustomerCreateService->execute(
                    account: $account,
                    customer: $customer,
                    wallet: $wallet,
                    customerType: CustomerType::PRIMARY
                );

                // Execute the AccountBalanceCreateService and return the resource
                $this->accountBalanceCreateService->execute(
                    account: $account,
                );

                // Execute the AccountCycleDefinition and return the resource
                $this->accountCycleDefinitionCreateService->execute(
                    account: $account,
                    cycleLength: $requestDTO->cycleLength,
                    expectedFrequencies: $requestDTO->expectedFrequencies,
                    payoutFrequencies: $requestDTO->payoutFrequencies,
                    commissionFrequencies: $requestDTO->commissionFrequencies,
                    expectedCycleAmount: $requestDTO->expectedCycleAmount,
                    expectedPayoutAmount: $requestDTO->expectedPayoutAmount,
                    commissionAmount: $requestDTO->commissionAmount,
                );

                // Execute the PaymentInstructionCreateService and return the resource
                $paymentInstruction = $this->paymentInstructionCreateService->execute(
                    account: $account,
                    transactionCategory: $transactionCategory,
                    accountCustomer: $accountCustomer,
                    transactionType: TransactionType::CREDIT,
                    wallet: $wallet,
                    amount: $requestDTO->susuAmount,
                    charge: $requestDTO->charge,
                    total: $requestDTO->susuAmount,
                    acceptedTerms: $requestDTO->acceptedTerms
                );

                // Execute the PaymentInstructionCreateService and return the resource
                $this->recurringDepositCreateService->execute(
                    account: $account,
                    accountCustomer: $accountCustomer,
                    paymentInstruction: $paymentInstruction,
                    frequency: $frequency,
                    recurringAmount: $requestDTO->susuAmount,
                    initialAmount: $requestDTO->initialDeposit,
                    initialDepositFrequency: $requestDTO->initialDepositFrequency,
                    rolloverEnabled: $requestDTO->rolloverEnabled,
                );

                // Return the DailySusu resource
                return $account->dailySusu()->create();
            });
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in DailySusuCreateService', [
                'customer' => $customer,
                'request_dto' => $requestDTO,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was a system failure while trying to create the daily susu.',
            );
        }
    }
}
