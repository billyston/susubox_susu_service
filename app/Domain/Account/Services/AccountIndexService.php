<?php

declare(strict_types=1);

namespace App\Domain\Account\Services;

use App\Domain\Account\Models\Account;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\IndividualAccount;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Throwable;

final class AccountIndexService
{
    /**
     * @param Customer $customer
     * @return Collection
     * @throws SystemFailureException
     */
    public static function execute(
        Customer $customer,
    ): Collection {
        try {
            // Query and return all active susu for the customer
            return Account::query()
                ->where('status', '!=', Statuses::CLOSED->value)
                ->whereHasMorph(
                    'accountable',
                    [IndividualAccount::class],
                    fn ($query) => $query->where('customer_id', $customer->id)
                )
                ->with([
                    'accountable.susuScheme',
                    'accountable.dailySusu',
                    'accountable.bizSusu',
                    'accountable.goalGetterSusu',
                    'accountable.flexySusu',
                    'accountable.driveToOwnSusu',
                ])
                ->orderBy('created_at', 'asc')
                ->get();
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in AccountIndexService', [
                'customer' => $customer,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was a system failure while trying to fetch the accounts.',
            );
        }
    }
}
