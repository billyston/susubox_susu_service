<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\GoalGetterSusu;

use App\Application\Shared\Helpers\Helpers;
use App\Domain\Account\Models\Account;
use App\Domain\Account\Models\AccountBalance;
use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Models\Wallet;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Models\Frequency;
use App\Domain\Shared\Models\SusuScheme;
use App\Domain\Susu\Models\IndividualSusu\GoalGetterSusu;
use App\Domain\Susu\Models\IndividualSusu\IndividualAccount;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class GoalGetterSusuCreateService
{
    /**
     * @param Customer $customer
     * @param SusuScheme $susuScheme
     * @param Frequency $frequency
     * @param Wallet $wallet
     * @param array $requestDTO
     * @return GoalGetterSusu
     * @throws SystemFailureException
     */
    public static function execute(
        Customer $customer,
        SusuScheme $susuScheme,
        Frequency $frequency,
        Wallet $wallet,
        array $requestDTO
    ): GoalGetterSusu {
        try {
            // Execute the database transaction
            return DB::transaction(function () use (
                $customer,
                $susuScheme,
                $frequency,
                $wallet,
                $requestDTO
            ) {
                // Create Financial Account
                $account = Account::create([
                    'accountable_type' => IndividualAccount::class,
                    'account_name' => $requestDTO['account_name'],
                    'account_number' => Account::generateAccountNumber(),
                    'accepted_terms' => $requestDTO['accepted_terms'],
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

                // Create and return the GoalGetterSusu resource
                return GoalGetterSusu::create([
                    'individual_account_id' => $individualAccount->id,
                    'wallet_id' => $wallet->id,
                    'frequency_id' => $frequency->id,
                    'duration_id' => $requestDTO['duration']['id'],
                    'target_amount' => $requestDTO['target_amount'],
                    'susu_amount' => $requestDTO['susu_amount'],
                    'initial_deposit' => $requestDTO['initial_deposit'],
                    'start_date' => $requestDTO['start_date'],
                    'end_date' => Helpers::getDateWithOffset(Carbon::parse($requestDTO['start_date']), days: $requestDTO['duration']['days']),
                ]);
            });
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in GoalGetterSusuCreateService', [
                'customer' => $customer,
                'susu_scheme' => $susuScheme,
                'frequency' => $frequency,
                'linked_wallet' => $wallet,
                'dto' => $requestDTO,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'A system error occurred while trying to create the goal getter susu.',
            );
        }
    }
}
