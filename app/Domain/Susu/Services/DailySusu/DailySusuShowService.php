<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\DailySusu;

use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use Illuminate\Support\Facades\Log;
use Throwable;

final class DailySusuShowService
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @return DailySusu
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public static function execute(
        Customer $customer,
        DailySusu $dailySusu,
    ): DailySusu {
        try {
            return match (true) {
                $dailySusu->individual->customer_id !== $customer->id => throw new UnauthorisedAccessException(
                    message: 'You are not authorized to access this susu account.'
                ),
                $dailySusu->individual->scheme->code !== config('susubox.susu_schemes.daily_susu_code') => throw new UnauthorisedAccessException(
                    message: 'You are not authorized to access this susu account.'
                ),

                // Return the DailySusu resource
                default => $dailySusu,
            };
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
                'error' => $throwable->getMessage(),
                'trace' => $throwable->getTraceAsString(),
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was a system failure while trying to fetch the daily susu account.',
            );
        }
    }
}
