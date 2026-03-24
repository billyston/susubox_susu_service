<?php

declare(strict_types=1);

namespace App\Domain\Account\Services\AccountCycleDefinition;

use App\Domain\Account\Models\Account;
use App\Domain\Account\Models\AccountCycleDefinition;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use Illuminate\Support\Facades\Log;
use Throwable;

final class AccountCycleDefinitionShowService
{
    /**
     * @param Customer $customer
     * @param Account $account
     * @param AccountCycleDefinition $accountCycleDefinition
     * @return AccountCycleDefinition
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public static function execute(
        Customer $customer,
        Account $account,
        AccountCycleDefinition $accountCycleDefinition
    ): AccountCycleDefinition {
        try {
            // Check customer, account and account cycle definition ownership
            $accountCycleDefinition = AccountCycleDefinition::query()
                ->where('id', $accountCycleDefinition->id)
                ->where('account_id', $account->id)
                ->whereHas('account.customers', function ($query) use ($customer) {
                    $query->where('customers.id', $customer->id);
                })
                ->first();

            // (Guard) Throw the UnauthorisedAccessException if check fails
            if (! $accountCycleDefinition) {
                throw new UnauthorisedAccessException(
                    message: 'You are not authorized to access this account cycle definition.'
                );
            }

            // Return the AccountCycleDefinition resource
            return $accountCycleDefinition;
        } catch (
            UnauthorisedAccessException $unauthorisedAccessException
        ) {
            throw $unauthorisedAccessException;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in AccountCycleDefinitionShowService', [
                'customer' => $customer,
                'account' => $account,
                'account_cycle_definition' => $accountCycleDefinition,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was a system failure while trying to fetch the account cycle definition.',
            );
        }
    }
}
