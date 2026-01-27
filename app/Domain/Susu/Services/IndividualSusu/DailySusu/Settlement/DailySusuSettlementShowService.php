<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\IndividualSusu\DailySusu\Settlement;

use App\Domain\Account\Models\AccountSettlement;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use Illuminate\Support\Facades\Log;
use Throwable;

final class DailySusuSettlementShowService
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param AccountSettlement $accountSettlement
     * @return AccountSettlement
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public static function execute(
        Customer $customer,
        DailySusu $dailySusu,
        AccountSettlement $accountSettlement,
    ): AccountSettlement {
        try {
            return match (true) {
                $dailySusu->individual->customer->id !== $customer->id => throw new UnauthorisedAccessException(
                    message: 'You are not authorized to access this susu account.'
                ),
                $dailySusu->account->id !== $accountSettlement->account->id => throw new UnauthorisedAccessException(
                    message: 'You are not authorized to access this susu account.'
                ),

                // Return the AccountSettlement resource
                default => $accountSettlement,
            };
        } catch (
            UnauthorisedAccessException $unauthorisedAccessException
        ) {
            throw $unauthorisedAccessException;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in DailySusuSettlementShowService', [
                'customer_id' => $customer,
                'daily_susu' => $dailySusu,
                'account_settlement' => $accountSettlement,
                'error' => $throwable->getMessage(),
                'trace' => $throwable->getTraceAsString(),
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was a system failure while trying to fetch the account settlement resource.',
            );
        }
    }
}
