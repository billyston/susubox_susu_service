<?php

declare(strict_types=1);

namespace App\Domain\Account\Services;

use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Throwable;

final class AccountIndexService
{
    /**
     * @throws SystemFailureException
     */
    public static function execute(
        Customer $customer,
    ): Collection {
        try {
            // Query and return all active susu for the customer
            return $customer
                ->accounts()
                ->orderBy(
                    column: 'created_at',
                    direction: 'asc'
                )->get();
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
                message: 'An error occurred while retrieving the accounts',
            );
        }
    }
}
