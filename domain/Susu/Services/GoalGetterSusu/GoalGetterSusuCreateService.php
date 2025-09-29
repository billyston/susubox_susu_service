<?php

declare(strict_types=1);

namespace Domain\Susu\Services\GoalGetterSusu;

use App\Common\Helpers\Helpers;
use App\Exceptions\Common\SystemFailureException;
use Brick\Money\Money;
use Domain\Customer\Models\Customer;
use Domain\Customer\Models\LinkedWallet;
use Domain\Shared\Models\AccountWallet;
use Domain\Shared\Models\Frequency;
use Domain\Shared\Models\SusuScheme;
use Domain\Susu\Models\Account;
use Domain\Susu\Models\GoalGetterSusu;
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
        LinkedWallet $linked_wallet,
        array $request_data
    ): GoalGetterSusu {
        try {
            // Execute the database transaction
            return DB::transaction(function () use (
                $customer,
                $susu_scheme,
                $frequency,
                $linked_wallet,
                $request_data
            ) {
                // Get the start_date of the account
                $start_date = Helpers::calculateDate(
                    date: $request_data['start_date']
                );

                // Get the total days in the savings duration
                $duration = Helpers::getDaysInDuration(
                    date: $request_data['duration']
                );

                // Calculate the susu_amount
                $susu_amount = Money::of(amount: self::getDebitAmount(
                    $request_data['target_amount'],
                    $request_data['frequency'],
                    $request_data['duration'],
                ), currency: 'GHS');

                // Create and return the account resource
                $account = Account::create([
                    'customer_id' => $customer->id,
                    'susu_scheme_id' => $susu_scheme->id,
                    'frequency_id' => $frequency->id,
                    'account_name' => $request_data['account_name'],
                    'account_number' => Account::generateAccountNumber(
                        product_code: config(key: 'susubox.susu_schemes.goal_getter_susu_code'),
                    ),
                    'purpose' => $request_data['purpose'],
                    'susu_amount' => $susu_amount,
                    'initial_deposit' => Money::of($request_data['initial_deposit'], currency: 'GHS'),
                    'start_date' => $start_date,
                    'end_date' => Helpers::getDateWithOffset(
                        Carbon::parse($start_date),
                        days: $duration->days
                    ),
                    'accepted_terms' => $request_data['accepted_terms'],
                ]);

                // Linked the account_wallet
                AccountWallet::create([
                    'account_id' => $account->id,
                    'linked_wallet_id' => $linked_wallet->id,
                ]);

                // Create and return the GoalGetterSusu resource
                return GoalGetterSusu::create([
                    'account_id' => $account->id,
                    'target_amount' => Money::of($request_data['target_amount'], currency: 'GHS'),
                    'duration_id' => $duration->id,
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
                'linked_wallet' => $linked_wallet,
                'request_data' => $request_data,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException;
        }
    }

    private static function getDebitAmount(
        $amount,
        $frequency,
        $duration
    ): float {
        return Helpers::calculateDebit(
            amount: (float) $amount,
            frequency: $frequency,
            duration: $duration
        );
    }
}
