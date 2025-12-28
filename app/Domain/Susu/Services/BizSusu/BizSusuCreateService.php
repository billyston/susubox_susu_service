<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\BizSusu;

use App\Application\Susu\DTOs\BizSusu\BizSusuCreateRequestDTO;
use App\Domain\Account\Models\Account;
use App\Domain\Account\Models\AccountBalance;
use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Services\CustomerWalletService;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Services\FrequencyService;
use App\Domain\Shared\Services\SusuSchemeService;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Domain\Susu\Models\IndividualSusu\IndividualAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class BizSusuCreateService
{
    private CustomerWalletService $customerLinkedWalletService;
    private SusuSchemeService $susuSchemeService;
    private FrequencyService $frequencyService;

    /**
     * @param CustomerWalletService $customerLinkedWalletService
     * @param SusuSchemeService $susuSchemeService
     * @param FrequencyService $frequencyService
     */
    public function __construct(
        CustomerWalletService $customerLinkedWalletService,
        SusuSchemeService $susuSchemeService,
        FrequencyService $frequencyService,
    ) {
        $this->customerLinkedWalletService = $customerLinkedWalletService;
        $this->susuSchemeService = $susuSchemeService;
        $this->frequencyService = $frequencyService;
    }

    /**
     * @param Customer $customer
     * @param BizSusuCreateRequestDTO $requestDTO
     * @return BizSusu
     * @throws SystemFailureException
     */
    public function execute(
        Customer $customer,
        BizSusuCreateRequestDTO $requestDTO
    ): BizSusu {
        try {
            // Execute the database transaction
            return DB::transaction(function () use (
                $customer,
                $requestDTO
            ) {
                // Execute the CustomerWalletService and return the resource
                $wallet = $this->customerLinkedWalletService->execute(
                    customer: $customer,
                    walletResourceID: $requestDTO->walletResourceID,
                );

                // Execute the SusuSchemeService and return the resource
                $susuScheme = $this->susuSchemeService->execute(
                    schemeCode: config(key: 'susubox.susu_schemes.biz_susu_code')
                );

                // Execute the FrequencyService and return the resource
                $frequency = $this->frequencyService->execute(
                    frequency_code: $requestDTO->frequency
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

                // Create and return the BizSusu resource
                return BizSusu::create([
                    'individual_account_id' => $individualAccount->id,
                    'wallet_id' => $wallet->id,
                    'frequency_id' => $frequency->id,
                    'susu_amount' => $requestDTO->susuAmount,
                    'initial_deposit' => $requestDTO->initialDeposit,
                    'rollover_enabled' => $requestDTO->rolloverEnabled,
                    'recurring_debit_status' => Statuses::PENDING->value,
                ]);
            });
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in BizSusuCreateService', [
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
                message: 'There was a system failure while trying to create the biz susu.',
            );
        }
    }
}
