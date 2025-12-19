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
            // Eager load relationships for efficiency
            $dailySusu->load([
                'individual.customer',
                'individual.account',
                'individual.susuScheme',
                'wallet',
                'frequency',
            ]);

            // Ensure DailySusu belongs to this customer through IndividualAccount
            if ($dailySusu->individualAccount->customer_id !== $customer->id) {
                throw new UnauthorisedAccessException(
                    message: 'You are not authorized to access this Daily Susu account.',
                );
            }

            // Ensure the account is for a Daily Susu scheme
            $individualAccount = $dailySusu->individualAccount;
            if (! $individualAccount->susuScheme) {
                throw new UnauthorisedAccessException(
                    message: 'This account is not a valid Daily Susu scheme.'
                );
            }

            // Ensure the polymorphic relationship is correct
            if ($individualAccount->susuable_type !== DailySusu::class) {
                throw new UnauthorisedAccessException(
                    message: 'Invalid account type configuration.'
                );
            }

            // Return the DailySusu resource with all relationships
            return $dailySusu;
        } catch (
            UnauthorisedAccessException $unauthorisedAccessException
        ) {
            // Log unauthorized access attempts
            Log::warning('Unauthorized access attempt to DailySusu', [
                'customer_id' => $customer->id,
                'daily_susu_id' => $dailySusu->id,
                'individual_account_customer_id' => $dailySusu->individualAccount->customer_id ?? 'unknown',
            ]);

            throw $unauthorisedAccessException;
        } catch (
            Throwable $throwable
        ) {
            // Log the error with specific context
            Log::error('Failed to fetch DailySusu', [
                'customer_id' => $customer->id,
                'daily_susu_id' => $dailySusu->id,
                'error' => $throwable->getMessage(),
                'trace' => $throwable->getTraceAsString(),
            ]);

            throw new SystemFailureException(
                message: 'Unable to retrieve Daily Susu account details. Please try again.',
            );
        }
    }
}
