<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\IndividualSusu\DailySusu\Account;

use App\Application\Susu\DTOs\IndividualSusu\DailySusu\Account\DailySusuCreateRequestDTO;
use App\Domain\Account\Models\Account;
use App\Domain\Account\Models\AccountBalance;
use App\Domain\Account\Models\AccountCycleDefinition;
use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Services\CustomerWalletService;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Services\FrequencyService;
use App\Domain\Shared\Services\SusuSchemeService;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Domain\Susu\Models\IndividualSusu\IndividualAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class DailySusuCreateService
{
    private CustomerWalletService $customerWalletService;
    private SusuSchemeService $susuSchemeService;
    private FrequencyService $frequencyService;

    /**
     * @param CustomerWalletService $customerWalletService
     * @param SusuSchemeService $susuSchemeService
     * @param FrequencyService $frequencyService
     */
    public function __construct(
        CustomerWalletService $customerWalletService,
        SusuSchemeService $susuSchemeService,
        FrequencyService $frequencyService,
    ) {
        $this->customerWalletService = $customerWalletService;
        $this->susuSchemeService = $susuSchemeService;
        $this->frequencyService = $frequencyService;
    }

    /**
     * @param Customer $customer
     * @param DailySusuCreateRequestDTO $requestDTO
     * @return DailySusu
     * @throws SystemFailureException
     */
    public function execute(
        Customer $customer,
        DailySusuCreateRequestDTO $requestDTO
    ): DailySusu {
        try {
            // Execute the database transaction
            return DB::transaction(function () use (
                $customer,
                $requestDTO
            ) {
                // Execute the CustomerWalletService and return the resource
                $wallet = $this->customerWalletService->execute(
                    customer: $customer,
                    walletResourceID: $requestDTO->walletResourceID,
                );

                // Execute the SusuSchemeService and return the resource
                $susuScheme = $this->susuSchemeService->execute(
                    schemeCode: config(key: 'susubox.susu_schemes.daily_susu_code')
                );

                // Execute the FrequencyService and return the resource
                $frequency = $this->frequencyService->execute(
                    frequency_code: 'daily'
                );

                // Create Financial Account
                $account = Account::create([
                    'accountable_type' => IndividualAccount::class,
                    'account_name' => $requestDTO->accountName,
                    'account_number' => Account::generateAccountNumber(),
                    'accepted_terms' => $requestDTO->acceptedTerms,
                ]);

                // Create IndividualAccount (polymorphic bridge)
                $individualAccount = IndividualAccount::create([
                    'customer_id' => $customer->id,
                    'susu_scheme_id' => $susuScheme->id,
                ]);

                // Link Account to IndividualAccount (Update polymorphic fields)
                $account->update([
                    'accountable_id' => $individualAccount->id,
                ]);

                // Create the AccountBalance
                AccountBalance::create([
                    'account_id' => $account->id,
                ]);

                // Create and return the DailySusu resource
                $dailySusu = DailySusu::create([
                    'individual_account_id' => $individualAccount->id,
                    'wallet_id' => $wallet->id,
                    'frequency_id' => $frequency->id,
                    'susu_amount' => $requestDTO->susuAmount,
                    'initial_deposit' => $requestDTO->initialDeposit,
                    'initial_deposit_frequency' => $requestDTO->initialDepositFrequency,
                    'rollover_enabled' => $requestDTO->rolloverEnabled,
                    'recurring_debit_status' => Statuses::PENDING->value,
                ]);

                // Create the AccountCycleDefinition
                AccountCycleDefinition::create([
                    'definable_type' => DailySusu::class,
                    'definable_id' => $dailySusu->id,
                    'cycle_length' => $requestDTO->cycleLength,
                    'expected_frequencies' => $requestDTO->expectedFrequencies,
                    'expected_cycle_amount' => $requestDTO->expectedCycleAmount,
                    'expected_settlement_amount' => $requestDTO->expectedSettlementAmount,
                    'commission_amount' => $requestDTO->commissionAmount,
                    'commission_frequencies' => $requestDTO->commissionFrequencies,
                    'settlement_frequencies' => $requestDTO->settlementFrequencies,
                ]);

                // Return the DailySusu resource
                return $dailySusu;
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
