<?php

declare(strict_types=1);

namespace App\Domain\Account\Services\AccountTransaction;

use App\Domain\Account\Models\Account;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\IndividualAccount;
use App\Domain\Transaction\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class AccountIndividualTransactionShowService
{
    /**
     * @throws UnauthorisedAccessException
     * @throws SystemFailureException
     */
    public static function execute(
        Customer $customer,
        Account $account,
        Transaction $transaction,
    ): Transaction {
        try {
            // Execute the database transaction
            return DB::transaction(function () use (
                $customer,
                $account,
                $transaction,
            ) {
                // Get the customer for the account
                $belongsToCustomer = $account
                    ->whereKey($account->id)
                    ->where('status', '!=', Statuses::CLOSED->value)
                    ->whereHasMorph(
                        'accountable',
                        [IndividualAccount::class],
                        fn ($query) => $query->where('customer_id', $customer->id)
                    )
                    ->exists();

                // Guard: Ensure the account belongs to the customer
                if (! $belongsToCustomer && $account->id !== $transaction->account_id) {
                    throw new UnauthorisedAccessException('You are not authorized to perform this action.');
                }

                // Return Transaction resource
                return $transaction->refresh();
            });
        } catch (
            UnauthorisedAccessException $unauthorisedAccessException
        ) {
            throw $unauthorisedAccessException;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in AccountIndividualTransactionShowService', [
                'customer' => $customer,
                'account' => $account,
                'transaction' => $transaction,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was a system failure while trying to retrieve the transaction.',
            );
        }
    }
}
