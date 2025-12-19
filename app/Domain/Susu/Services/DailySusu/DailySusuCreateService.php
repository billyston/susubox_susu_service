<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\DailySusu;

use App\Domain\Account\Models\Account;
use App\Domain\Account\Models\AccountBalance;
use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Models\Wallet;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Models\Frequency;
use App\Domain\Shared\Models\SusuScheme;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Domain\Susu\Models\IndividualSusu\IndividualAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class DailySusuCreateService
{
    /**
     * @param Customer $customer
     * @param SusuScheme $susuScheme
     * @param Frequency $frequency
     * @param Wallet $wallet
     * @param array $requestDTO
     * @return DailySusu
     * @throws SystemFailureException
     */
    public static function execute(
        Customer $customer,
        SusuScheme $susuScheme,
        Frequency $frequency,
        Wallet $wallet,
        array $requestDTO
    ): DailySusu {
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

                // Create and return the DailySusu resource
                return DailySusu::create([
                    'individual_account_id' => $individualAccount->id,
                    'wallet_id' => $wallet->id,
                    'frequency_id' => $frequency->id,
                    'susu_amount' => $requestDTO['susu_amount'],
                    'initial_deposit' => $requestDTO['initial_deposit'],
                    'rollover_enabled' => $requestDTO['rollover_enabled'],
                    'recurring_debit_status' => Statuses::PENDING->value,
                ]);
            });
        } catch (
            Throwable $throwable
        ) {
            Log::error('Exception in BizSusuCreateService', [
                'customer' => $customer,
                'susu_scheme' => $susuScheme,
                'frequency' => $frequency,
                'wallet' => $wallet,
                'dto' => $requestDTO,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            throw new SystemFailureException(
                message: 'Failed to create the daily susu account. Please try again.',
            );
        }
    }
}
