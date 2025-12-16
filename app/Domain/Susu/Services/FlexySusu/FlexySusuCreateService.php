<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\FlexySusu;

use App\Domain\Account\Models\Account;
use App\Domain\Account\Models\AccountBalance;
use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Models\Wallet;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Models\SusuScheme;
use App\Domain\Susu\Models\IndividualSusu\FlexySusu;
use App\Domain\Susu\Models\IndividualSusu\IndividualAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class FlexySusuCreateService
{
    /**
     * @throws SystemFailureException
     */
    public static function execute(
        Customer $customer,
        SusuScheme $susu_scheme,
        Wallet $wallet,
        array $dto
    ): FlexySusu {
        try {
            // Execute the database transaction
            return DB::transaction(function () use (
                $customer,
                $susu_scheme,
                $wallet,
                $dto
            ) {
                // Create Financial Account
                $account = Account::create([
                    'accountable_type' => IndividualAccount::class,
                    'account_name' => $dto['account_name'],
                    'account_number' => Account::generateAccountNumber(),
                    'accepted_terms' => $dto['accepted_terms'],
                ]);

                // Create IndividualAccount (polymorphic bridge)
                $individualAccount = IndividualAccount::create([
                    'customer_id' => $customer->id,
                    'susu_scheme_id' => $susu_scheme->id,
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
                    'initial_deposit' => $dto['initial_deposit'],
                ]);
            });
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in FlexySusuCreateService', [
                'customer' => $customer,
                'susu_scheme' => $susu_scheme,
                'linked_wallet' => $wallet,
                'dto' => $dto,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'A system failure occurred while trying to create the flexy susu.',
            );
        }
    }
}
