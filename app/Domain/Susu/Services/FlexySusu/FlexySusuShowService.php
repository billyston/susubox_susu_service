<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\FlexySusu;

use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\FlexySusu;
use Illuminate\Support\Facades\Log;
use Throwable;

final class FlexySusuShowService
{
    /**
     * @param Customer $customer
     * @param FlexySusu $flexySusu
     * @return FlexySusu
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public static function execute(
        Customer $customer,
        FlexySusu $flexySusu,
    ): FlexySusu {
        try {
            return match (true) {
                $flexySusu->individual->customer_id !== $customer->id => throw new UnauthorisedAccessException(
                    message: 'You are not authorized to access this susu account.'
                ),
                $flexySusu->individual->susuScheme->code !== config('susubox.susu_schemes.flexy_susu_code') => throw new UnauthorisedAccessException(
                    message: 'You are not authorized to access this susu account.'
                ),

                // Return the FlexySusu resource
                default => $flexySusu,
            };
        } catch (
            UnauthorisedAccessException $unauthorisedAccessException
        ) {
            throw $unauthorisedAccessException;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in FlexySusuGetService', [
                'customer' => $customer,
                'flexy_susu' => $flexySusu,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was a system failure while trying to fetch the flexy susu account.',
            );
        }
    }
}
