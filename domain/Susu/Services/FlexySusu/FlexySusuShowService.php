<?php

declare(strict_types=1);

namespace Domain\Susu\Services\FlexySusu;

use App\Exceptions\Common\SystemFailureException;
use Domain\Customer\Models\Customer;
use Domain\Shared\Exceptions\UnauthorisedAccessException;
use Domain\Susu\Models\Account;
use Domain\Susu\Models\FlexySusu;
use Illuminate\Support\Facades\Log;
use Throwable;

final class FlexySusuShowService
{
    /**
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public static function execute(
        Customer $customer,
        Account $account,
    ): FlexySusu {
        try {
            // Ensure account belongs to this customer
            if ($account->customer_id !== $customer->id) {
                throw new UnauthorisedAccessException;
            }

            // Ensure the account is for a Daily Susu scheme
            if ($account->scheme->code !== config(key: 'susubox.susu_schemes.flexy_susu_code')) {
                throw new UnauthorisedAccessException;
            }

            // Return the FlexySusu resource
            return $account->flexy;
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
                'account' => $account,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException;
        }
    }
}
