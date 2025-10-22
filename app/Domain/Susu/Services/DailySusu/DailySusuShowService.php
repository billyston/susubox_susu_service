<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\DailySusu;

use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\DailySusu;
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
        DailySusu $daily_susu,
    ): DailySusu {
        try {
            // Ensure account belongs to this customer
            if ($daily_susu->account->customer_id !== $customer->id) {
                throw new UnauthorisedAccessException;
            }

            // Ensure the account is for a Daily Susu scheme
            if ($daily_susu->account->scheme->code !== config(key: 'susubox.susu_schemes.daily_susu_code')) {
                throw new UnauthorisedAccessException;
            }

            // Return the DailySusu resource
            return $daily_susu;
        } catch (
            UnauthorisedAccessException $unauthorisedAccessException
        ) {
            throw $unauthorisedAccessException;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in DailySusuShowService', [
                'customer' => $customer,
                'daily_susu' => $daily_susu,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was an error while fetching the daily susu account.',
            );
        }
    }
}
