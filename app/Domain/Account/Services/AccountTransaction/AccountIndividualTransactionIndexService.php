<?php

declare(strict_types=1);

namespace App\Domain\Account\Services\AccountTransaction;

use App\Domain\Account\Models\Account;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\IndividualAccount;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class AccountIndividualTransactionIndexService
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
            // Execute the database transaction
            return DB::transaction(function () use (
                $customer,
                $account,
                $perPage
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
                if (! $belongsToCustomer) {
                    throw new UnauthorisedAccessException('You are not authorized to perform this action.');
                }

                // Get all Transaction for the Account
                return $account
                    ->transactions()
                    ->orderBy('created_at')
                    ->paginate($perPage);
            });
        } catch (
            UnauthorisedAccessException $unauthorisedAccessException
        ) {
            throw $unauthorisedAccessException;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in AccountIndividualTransactionIndexService', [
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
