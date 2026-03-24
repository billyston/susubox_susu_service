<?php

declare(strict_types=1);

namespace App\Domain\Account\Services\AccountRecurringDeposit;

use App\Domain\Account\Models\Account;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Models\RecurringDeposit;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use Illuminate\Support\Facades\Log;
use Throwable;

final class AccountRecurringDepositShowService
{
    /**
     * @param Customer $customer
     * @param Account $account
     * @param RecurringDeposit $recurringDeposit
     * @return RecurringDeposit
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public static function execute(
        Customer $customer,
        Account $account,
        RecurringDeposit $recurringDeposit
    ): RecurringDeposit {
        try {
            // Check customer, account and account cycle definition ownership
            $recurringDeposit = RecurringDeposit::query()
                ->where('id', $recurringDeposit->id)
                ->where('account_id', $account->id)
                ->whereHas('account.customers', function ($query) use ($customer) {
                    $query->where('customers.id', $customer->id);
                })
                ->first();

            // (Guard) Throw the UnauthorisedAccessException if check fails
            if (! $recurringDeposit) {
                throw new UnauthorisedAccessException(
                    message: 'You are not authorized to access this recurring deposit.'
                );
            }

            // Return the AccountCycleDefinition resource
            return $recurringDeposit;
        } catch (
            UnauthorisedAccessException $unauthorisedAccessException
        ) {
            throw $unauthorisedAccessException;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in AccountRecurringDepositShowService', [
                'customer' => $customer,
                'account' => $account,
                'account_cycle_definition' => $recurringDeposit,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was a system failure while trying to fetch the recurring deposit.',
            );
        }
    }
}
