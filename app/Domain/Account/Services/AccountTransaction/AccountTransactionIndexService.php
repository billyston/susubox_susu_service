<?php

declare(strict_types=1);

namespace App\Domain\Account\Services\AccountTransaction;

use App\Domain\Account\Models\Account;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Transaction\Models\Transaction;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Throwable;

final class AccountTransactionIndexService
{
    /**
     * @param Customer $customer
     * @param Account $account
     * @param int $perPage
     * @return LengthAwarePaginator
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public static function execute(
        Customer $customer,
        Account $account,
        int $perPage = 20,
    ): LengthAwarePaginator {
        try {
            // Ensure account belongs to customer
            $belongsToCustomer = $account->customers()
                ->where('customers.id', $customer->id)
                ->wherePivot('status', 'active')
                ->exists();

            // (Guard): Throw UnauthorisedAccessException if customer has no link to the account
            if (! $belongsToCustomer) {
                throw new UnauthorisedAccessException('You are not authorized to perform this action.');
            }

            // Get all wallet IDs linked to this account via account_customers
            $walletIds = $account->accountCustomers()
                ->pluck('wallet_id');

            // Fetch transactions:
            return Transaction::query()
                ->where(function ($query) use ($account, $walletIds) {
                    $query->where('account_id', $account->id)
                        ->orWhereIn('wallet_id', $walletIds);
                })
                ->orderByDesc('created_at')
                ->paginate($perPage);
        } catch (
            UnauthorisedAccessException $unauthorisedAccessException
        ) {
            throw $unauthorisedAccessException;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in AccountTransactionIndexService', [
                'customer' => $customer,
                'account' => $account,
                'per_page' => $perPage,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was a system failure while trying to fetch the account transactions.',
            );
        }
    }
}
