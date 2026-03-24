<?php

declare(strict_types=1);

namespace App\Domain\Account\Services\AccountTransaction;

use App\Domain\Account\Models\Account;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Transaction\Models\Transaction;
use Illuminate\Support\Facades\Log;
use Throwable;

final class AccountTransactionShowService
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
            // Ensure transaction belongs to the account and customer
            $transaction = Transaction::query()
                ->where('id', $transaction->id)
                ->where('account_id', $account->id)
                ->whereHas('account.customers', function ($query) use ($customer) {
                    $query->where('customers.id', $customer->id);
                })
                ->first();

            // (Guard): Throw UnauthorisedAccessException if $paymentInstruction fails
            if (! $transaction) {
                throw new UnauthorisedAccessException(
                    message: 'You are not authorized to access this payment instruction.'
                );
            }

            // Return the Transaction resource
            return $transaction;
        } catch (
            UnauthorisedAccessException $throwable
        ) {
            throw $throwable;
        } catch (
            Throwable $throwable
        ) {
            Log::error('Exception in AccountTransactionShowService', [
                'customer' => $customer,
                'account' => $account,
                'transaction' => $transaction,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            throw new SystemFailureException(
                message: 'There was a system failure while trying to retrieve the transaction.',
            );
        }
    }
}
