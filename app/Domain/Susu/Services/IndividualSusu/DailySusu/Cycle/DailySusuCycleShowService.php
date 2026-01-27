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
     *
     * @return AccountCycle
     *
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
                /**
                 * AUTHORISATION & OWNERSHIP GUARDS
                 */
                match (true) {
                    // DailySusu does not belong to customer
                    $dailySusu->individual->customer_id !== $customer->id => throw new UnauthorisedAccessException(
                        message: 'You are not authorised to access this Daily Susu.'
                    ),

                    // AccountCycle does not belong to DailySusu
                    $accountCycle->cycleable_type !== DailySusu::class ||
                    $accountCycle->cycleable_id !== $dailySusu->id => throw new UnauthorisedAccessException(
                        message: 'This account cycle does not belong to the specified Daily Susu.'
                    ),

                    default => null,
                };

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
                'customer_id' => $customer->id,
                'daily_susu_id' => $dailySusu->id,
                'account_cycle_id' => $accountCycle->id,
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
