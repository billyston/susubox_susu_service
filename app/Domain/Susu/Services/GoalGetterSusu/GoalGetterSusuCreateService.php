<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\GoalGetterSusu;

use App\Application\Shared\Helpers\Helpers;
use App\Application\Susu\DTOs\GoalGetterSusu\GoalGetterSusuCreateDTO;
use App\Domain\Account\Models\Account;
use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Models\LinkedWallet;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Models\AccountWallet;
use App\Domain\Shared\Models\Frequency;
use App\Domain\Shared\Models\SusuScheme;
use App\Domain\Susu\Models\GoalGetterSusu;
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
        GoalGetterSusuCreateDTO $dto
    ): GoalGetterSusu {
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
                    'account_number' => Account::generateAccountNumber(product_code: config(key: 'susubox.susu_schemes.goal_getter_susu_code')),
                    'purpose' => $dto->purpose,
                    'susu_amount' => $dto->susu_amount,
                    'initial_deposit' => $dto->initial_deposit,
                    'start_date' => $dto->start_date,
                    'end_date' => Helpers::getDateWithOffset(Carbon::parse($dto->start_date), days: $dto->duration->days),
                    'accepted_terms' => $dto->accepted_terms,
                ]);

                // Linked the account_wallet
                AccountWallet::query()->create([
                    'account_id' => $account->id,
                    'linked_wallet_id' => $linked_wallet->id,
                ]);

                // Create and return the GoalGetterSusu resource
                return GoalGetterSusu::query()->create([
                    'account_id' => $account->id,
                    'frequency_id' => $frequency->id,
                    'target_amount' => $dto->target_amount,
                    'duration_id' => $dto->duration->id,
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
