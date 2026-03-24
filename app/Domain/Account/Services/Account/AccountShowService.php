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
            // Ensure account belongs to customer
            $account = Account::query()
                ->where('id', $account->id)
                ->where('status', '!=', Statuses::CLOSED->value)
                ->whereHas('accountCustomers', function ($query) use ($customer) {
                    $query->where('customer_id', $customer->id);
                })
                ->firstOrFail();

            // (Guard): Throw UnauthorisedAccessException if $paymentInstruction fails
            if (! $account) {
                throw new UnauthorisedAccessException(
                    message: 'You are not authorized to access this payment instruction.'
                );
            }

            // Return the Account resource
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
