<?php

declare(strict_types=1);

namespace App\Domain\Account\Services\Account;

use App\Domain\Account\Models\Account;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
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
            // Execute the database transaction
            return Account::query()
                ->where('status', '!=', Statuses::CLOSED->value)
                ->whereHas('accountCustomers', function ($query) use ($customer) {
                    $query->where('customer_id', $customer->id);
                })
                ->orderByDesc('created_at')
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
