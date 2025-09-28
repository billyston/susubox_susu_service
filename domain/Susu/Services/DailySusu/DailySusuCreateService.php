<?php

declare(strict_types=1);

namespace Domain\Susu\Services\DailySusu;

use App\Exceptions\Common\SystemFailureException;
use Brick\Money\Money;
use Domain\Customer\Models\Customer;
use Domain\Customer\Models\LinkedWallet;
use Domain\Shared\Models\AccountWallet;
use Domain\Shared\Models\Frequency;
use Domain\Shared\Models\SusuScheme;
use Domain\Susu\Models\Account;
use Domain\Susu\Models\DailySusu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class DailySusuCreateService
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
    ): DailySusu {
        try {
            // Execute the database transaction
            return DB::transaction(function () use (
                $customer,
                $susu_scheme,
                $frequency,
                $linked_wallet,
                $request_data
            ) {
                // Create and return the account resource
                $account = Account::create([
                    'customer_id' => $customer->id,
                    'susu_scheme_id' => $susu_scheme->id,
                    'frequency_id' => $frequency->id,
                    'account_name' => $request_data['account_name'],
                    'account_number' => Account::generateAccountNumber(
                        product_code: config(key: 'susubox.susu_schemes.daily_susu_code'),
                    ),
                    'purpose' => $request_data['purpose'],
                    'amount' => Money::of($request_data['susu_amount'], currency: 'GHS'),
                    'accepted_terms' => $request_data['accepted_terms'],
                ]);

                // Linked the account_wallet
                AccountWallet::create([
                    'account_id' => $account->id,
                    'linked_wallet_id' => $linked_wallet->id,
                ]);

                // Create and return the DailySusu resource
                return DailySusu::create([
                    'account_id' => $account->id,
                    'initial_deposit' => $account->amount->multipliedBy($request_data['initial_deposit']),
                    'rollover_enabled' => $request_data['rollover_enabled'],
                ]);
            });
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in DailySusuCreateService', [
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
}
