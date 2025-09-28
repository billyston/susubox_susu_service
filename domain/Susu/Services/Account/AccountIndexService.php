<?php

declare(strict_types=1);

namespace Domain\Susu\Services\Account;

use App\Exceptions\Common\SystemFailureException;
use Domain\Customer\Models\Customer;
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
            throw new SystemFailureException;
        }
    }
}
