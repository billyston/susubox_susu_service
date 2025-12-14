<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\GoalGetterSusu;

use App\Application\Shared\Helpers\Helpers;
use App\Domain\Account\Models\Account;
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
     * @throws SystemFailureException
     */
    public static function execute(
        Customer $customer,
        SusuScheme $susu_scheme,
        Frequency $frequency,
        Wallet $wallet,
        array $dto
    ): GoalGetterSusu {
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
                    'customer_id' => $customer->id,
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

                // Create and return the GoalGetterSusu resource
                return GoalGetterSusu::query()->create([
                    'individual_account_id' => $individualAccount->id,
                    'wallet_id' => $wallet->id,
                    'frequency_id' => $frequency->id,
                    'duration_id' => $dto['duration']['id'],
                    'target_amount' => $dto['target_amount'],
                    'susu_amount' => $dto['susu_amount'],
                    'initial_deposit' => $dto['initial_deposit'],
                    'start_date' => $dto['start_date'],
                    'end_date' => Helpers::getDateWithOffset(Carbon::parse($dto['start_date']), days: $dto['duration']['days']),
                ]);
            });
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in GoalGetterSusuCreateService', [
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
                message: 'A system error occurred while trying to create the goal getter susu.',
            );
        }
    }
}
