<?php

declare(strict_types=1);

namespace Domain\Susu\Services\DailySusu;

use App\Exceptions\Common\SystemFailureException;
use Domain\Customer\Models\Customer;
use Domain\Shared\Exceptions\UnauthorisedAccessException;
use Domain\Susu\Models\Account;
use Illuminate\Support\Facades\Log;
use Throwable;

final class DailySusuGetService
{
    /**
     * @throws SystemFailureException
     */
    public static function execute(
        Customer $customer,
        Account $account,
    ): Account {
        try {
            // Ensure account belongs to this customer
            if ($account->customer_id !== $customer->id) {
                throw new UnauthorisedAccessException(message: 'Account is not the same as the customer.');
            }

            // Eager-load all necessary relationships for the resource
            return $account->load([
                'scheme',
                'daily',
                'wallets',
//                'daily.stats',
            ]);
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in DailySusuGetService', [
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
