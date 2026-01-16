<?php

declare(strict_types=1);

namespace App\Domain\Account\Services\Account;

use App\Domain\Account\Models\Account;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use Illuminate\Support\Facades\Log;
use Throwable;

final class AccountShowService
{
    /**
     * @param Customer $customer
     * @param Account $account
     * @return Account
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public static function execute(
        Customer $customer,
        Account $account,
    ): Account {
        try {
            // Get the IndividualAccount
            $individualAccount = $account->accountable;

            match (true) {
                // Account is closed
                $account->status === Statuses::CLOSED->value => throw new UnauthorisedAccessException(
                    'This account has been closed.'
                ),

                // Account does not belong to customer
                $individualAccount->customer_id !== $customer->id => throw new UnauthorisedAccessException(
                    'You are not authorized to perform this action.'
                ),

                default => null,
            };

            // Return the account
            return $account;
        } catch (
            UnauthorisedAccessException $unauthorisedAccessException
        ) {
            throw $unauthorisedAccessException;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in AccountShowService', [
                'customer' => $customer,
                'account' => $account,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was a system failure while trying to retrieve the account.',
            );
        }
    }
}
