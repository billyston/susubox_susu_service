<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\FlexySusu;

use App\Application\Susu\DTOs\FlexySusu\FlexySusuCreateRequestDTO;
use App\Domain\Account\Models\Account;
use App\Domain\Account\Models\AccountBalance;
use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Services\CustomerWalletService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Services\SusuSchemeService;
use App\Domain\Susu\Models\IndividualSusu\FlexySusu;
use App\Domain\Susu\Models\IndividualSusu\IndividualAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class FlexySusuCreateService
{
    private CustomerWalletService $customerLinkedWalletService;
    private SusuSchemeService $susuSchemeService;

    /**
     * @param CustomerWalletService $customerLinkedWalletService
     * @param SusuSchemeService $susuSchemeService
     */
    public function __construct(
        CustomerWalletService $customerLinkedWalletService,
        SusuSchemeService $susuSchemeService,
    ) {
        $this->customerLinkedWalletService = $customerLinkedWalletService;
        $this->susuSchemeService = $susuSchemeService;
    }

    /**
     * @param Customer $customer
     * @param FlexySusuCreateRequestDTO $requestDTO
     * @return FlexySusu
     * @throws SystemFailureException
     */
    public function execute(
        Customer $customer,
        FlexySusuCreateRequestDTO $requestDTO
    ): FlexySusu {
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
                    schemeCode: config(key: 'susubox.susu_schemes.flexy_susu_code')
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

                // Create and return the FlexySusu resource
                return FlexySusu::create([
                    'individual_account_id' => $individualAccount->id,
                    'wallet_id' => $wallet->id,
                    'initial_deposit' => $requestDTO->initialDeposit,
                ]);
            });
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in FlexySusuCreateService', [
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
                message: 'There was a system error while trying to create the flexy susu.',
            );
        }
    }
}
