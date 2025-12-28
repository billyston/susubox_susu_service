<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\GoalGetterSusu;

use App\Application\Shared\Helpers\Helpers;
use App\Application\Susu\DTOs\GoalGetterSusu\GoalGetterSusuCreateRequestDTO;
use App\Domain\Account\Models\Account;
use App\Domain\Account\Models\AccountBalance;
use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Services\CustomerWalletService;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Services\FrequencyService;
use App\Domain\Shared\Services\SusuSchemeService;
use App\Domain\Susu\Models\IndividualSusu\GoalGetterSusu;
use App\Domain\Susu\Models\IndividualSusu\IndividualAccount;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class GoalGetterSusuCreateService
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
     * @param GoalGetterSusuCreateRequestDTO $requestDTO
     * @return GoalGetterSusu
     * @throws SystemFailureException
     */
    public function execute(
        Customer $customer,
        GoalGetterSusuCreateRequestDTO $requestDTO
    ): GoalGetterSusu {
        try {
            // Execute the database transaction
            return DB::transaction(function () use (
                $customer,
                $requestDTO,
            ) {
                // Execute the CustomerWalletService and return the resource
                $wallet = $this->customerLinkedWalletService->execute(
                    customer: $customer,
                    walletResourceID: $requestDTO->wallet_id,
                );

                // Execute the SusuSchemeService and return the resource
                $susuScheme = $this->susuSchemeService->execute(
                    schemeCode: config(key: 'susubox.susu_schemes.goal_getter_susu_code')
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

                // Get the end_date
                $endDate = Helpers::getDateWithOffset(
                    date: Carbon::parse($requestDTO->startDate),
                    days: $requestDTO->duration['days']
                );

                // Create the GoalGetterSusu
                $goalGetterSusu = GoalGetterSusu::create([
                    'individual_account_id' => $individualAccount->id,
                    'wallet_id' => $wallet->id,
                    'frequency_id' => $frequency->id,
                    'duration_id' => $requestDTO->duration['id'],
                    'target_amount' => $requestDTO->targetAmount,
                    'susu_amount' => $requestDTO->susuAmount,
                    'initial_deposit' => $requestDTO->initialDeposit,
                    'start_date' => $requestDTO->startDate,
                    'end_date' => $endDate,
                ]);

                // Create the AccountLock
                $goalGetterSusu->accountLocks()->create([
                    'locked_at' => $requestDTO->startDate,
                    'unlocked_at' => $endDate,
                    'accepted_terms' => true,
                    'status' => Statuses::ACTIVE->value,
                ]);

                // Return the GoalGetterSusu resource
                return $goalGetterSusu;
            });
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in GoalGetterSusuCreateService', [
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
                message: 'There was a system error while trying to create the goal getter susu.',
            );
        }
    }
}
