<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\BizSusu;

use App\Domain\Account\Models\Account;
use App\Domain\Account\Models\AccountBalance;
use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Models\Wallet;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Models\Frequency;
use App\Domain\Shared\Models\SusuScheme;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Domain\Susu\Models\IndividualSusu\IndividualAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class BizSusuCreateService
{
    /**
     * @throws SystemFailureException
     */
    public static function execute(
        Customer $customer,
        SusuScheme $susu_scheme,
        Frequency $frequency,
        Wallet $wallet,
        array $dto
    ): BizSusu {
        try {
            // Execute the database transaction
            return DB::transaction(function () use (
                $customer,
                $susu_scheme,
                $frequency,
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

                // Create and return the BizSusu resource
                return BizSusu::create([
                    'individual_account_id' => $individualAccount->id,
                    'wallet_id' => $wallet->id,
                    'frequency_id' => $frequency->id,
                    'susu_amount' => $dto['susu_amount'],
                    'initial_deposit' => $dto['initial_deposit'],
                    'rollover_enabled' => $dto['rollover_enabled'],
                    'recurring_debit_status' => Statuses::PENDING->value,
                ]);
            });
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in BizSusuCreateService', [
                'customer' => $customer,
                'susu_scheme' => $susu_scheme,
                'frequency' => $frequency,
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
                message: 'A system failure occurred while trying to create biz susu scheme.',
            );
        }
    }
}
