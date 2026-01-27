<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\IndividualSusu\BizSusu\Account;

use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use Illuminate\Support\Facades\Log;
use Throwable;

final class BizSusuShowService
{
    /**
     * @param Customer $customer
     * @param BizSusu $bizSusu
     * @return BizSusu
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public static function execute(
        Customer $customer,
        BizSusu $bizSusu,
    ): BizSusu {
        try {
            return match (true) {
                $bizSusu->individual->customer_id !== $customer->id => throw new UnauthorisedAccessException(
                    message: 'You are not authorized to access this susu account.'
                ),
                $bizSusu->individual->susuScheme->code !== config('susubox.susu_schemes.biz_susu_code') => throw new UnauthorisedAccessException(
                    message: 'You are not authorized to access this susu account.'
                ),

                // Return the BizSusu resource
                default => $bizSusu,
            };
        } catch (
            UnauthorisedAccessException $unauthorisedAccessException
        ) {
            throw $unauthorisedAccessException;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in BizSusuShowService', [
                'customer' => $customer,
                'biz_susu' => $bizSusu,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was a system failure while trying to fetch the biz susu account.',
            );
        }
    }
}
