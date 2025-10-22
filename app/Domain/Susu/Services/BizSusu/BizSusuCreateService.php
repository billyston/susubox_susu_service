<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\BizSusu;

use App\Domain\Account\Models\Account;
use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Models\LinkedWallet;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Models\AccountWallet;
use App\Domain\Shared\Models\Frequency;
use App\Domain\Shared\Models\SusuScheme;
use App\Domain\Susu\Models\BizSusu;
use Brick\Money\Money;
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
        LinkedWallet $linked_wallet,
        array $request_data
    ): BizSusu {
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
                    'account_name' => $request_data['account_name'],
                    'account_number' => Account::generateAccountNumber(
                        product_code: config(key: 'susubox.susu_schemes.biz_susu_code'),
                    ),
                    'purpose' => $request_data['purpose'],
                    'susu_amount' => Money::of($request_data['susu_amount'], currency: 'GHS'),
                    'initial_deposit' => Money::of($request_data['initial_deposit'], currency: 'GHS'),
                    'accepted_terms' => $request_data['accepted_terms'],
                ]);

                // Linked the account_wallet
                AccountWallet::create([
                    'account_id' => $account->id,
                    'linked_wallet_id' => $linked_wallet->id,
                ]);

                // Create and return the BizSusu resource
                return BizSusu::create([
                    'account_id' => $account->id,
                    'frequency_id' => $frequency->id,
                    'rollover_enabled' => $request_data['rollover_enabled'],
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
                'linked_wallet' => $linked_wallet,
                'request_data' => $request_data,
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
