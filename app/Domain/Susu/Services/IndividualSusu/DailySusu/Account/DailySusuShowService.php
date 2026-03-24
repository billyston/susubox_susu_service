<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\IndividualSusu\DailySusu\Account;

use App\Domain\Account\Models\AccountCustomer;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use Illuminate\Support\Facades\Log;
use Throwable;

final class DailySusuShowService
{
    /**
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public static function execute(
        Customer $customer,
        DailySusu $dailySusu,
    ): DailySusu {
        try {
            // Ensure the customer belongs to the DailySusu via account_customers
            $belongsToCustomer = AccountCustomer::query()
                ->where('customer_id', $customer->id)
                ->where('account_id', $dailySusu->account_id)
                ->exists();

            // Guard check
            if (! $belongsToCustomer) {
                throw new UnauthorisedAccessException(
                    message: 'You are not authorized to access this susu account.'
                );
            }

            // Return the DailySusu resource
            return $dailySusu;
        } catch (
            UnauthorisedAccessException $unauthorisedAccessException
        ) {
            throw $unauthorisedAccessException;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in DailySusuShowService', [
                'customer_id' => $customer,
                'daily_susu' => $dailySusu,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                    'trace' => $throwable->getTraceAsString(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was a system failure while trying to fetch the daily susu account.',
            );
        }
    }
}
