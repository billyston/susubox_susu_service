<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\IndividualSusu\DailySusu\Cycle;

use App\Domain\Account\Models\AccountCycle;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class DailySusuCycleShowService
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param AccountCycle $accountCycle
     * @return AccountCycle
     * @throws UnauthorisedAccessException
     * @throws SystemFailureException
     */
    public static function execute(
        Customer $customer,
        DailySusu $dailySusu,
        AccountCycle $accountCycle,
    ): AccountCycle {
        try {
            return DB::transaction(function () use (
                $customer,
                $dailySusu,
                $accountCycle
            ) {
                // Extract the main resources
                $account = $dailySusu->account;
                $accountCustomer = $account->accountCustomer->customer;

                // Ensure the customer making the request is the owner of the account
                // And account belongs to the accountCycle
                if ($accountCustomer->id !== $customer->id && $account->id !== $accountCycle->account_id) {
                    throw new UnauthorisedAccessException(
                        message: 'You are not authorised to access these account cycle.'
                    );
                }

                // Return the AccountCycle
                return $accountCycle;
            });
        } catch (
            UnauthorisedAccessException $exception
        ) {
            throw $exception;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in DailySusuCycleShowService', [
                'customer' => $customer,
                'daily_susu' => $dailySusu,
                'account_cycle' => $accountCycle,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was a system failure while fetching the daily susu account cycle.',
            );
        }
    }
}
