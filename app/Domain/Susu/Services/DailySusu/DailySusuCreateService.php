<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\DailySusu;

use App\Application\Susu\DTOs\DailySusu\DailySusuCreateDTO;
use App\Domain\Account\Models\Account;
use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Models\LinkedWallet;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Models\AccountWallet;
use App\Domain\Shared\Models\Frequency;
use App\Domain\Shared\Models\SusuScheme;
use App\Domain\Susu\Models\DailySusu;
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
        DailySusuCreateDTO $dto
    ): DailySusu {
        try {
            // Execute the database transaction
            return DB::transaction(function () use (
                $customer,
                $susu_scheme,
                $frequency,
                $linked_wallet,
                $dto
            ) {
                // Create and return the account resource
                $account = Account::query()->create([
                    'customer_id' => $customer->id,
                    'susu_scheme_id' => $susu_scheme->id,
                    'account_name' => $dto->account_name,
                    'account_number' => Account::generateAccountNumber(product_code: config(key: 'susubox.susu_schemes.daily_susu_code')),
                    'purpose' => $dto->purpose,
                    'susu_amount' => $dto->susu_amount,
                    'initial_deposit' => $dto->susu_amount->multipliedBy($dto->initial_deposit),
                    'accepted_terms' => $dto->accepted_terms,
                ]);

                // Linked the account_wallet
                AccountWallet::query()->create([
                    'account_id' => $account->id,
                    'linked_wallet_id' => $linked_wallet->id,
                ]);

                // Create and return the DailySusu resource
                return DailySusu::query()->create([
                    'account_id' => $account->id,
                    'frequency_id' => $frequency->id,
                    'rollover_enabled' => $dto->rollover_enabled,
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
                'dto' => $dto,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'A system failure occurred while trying to create the daily susu.',
            );
        }
    }
}
