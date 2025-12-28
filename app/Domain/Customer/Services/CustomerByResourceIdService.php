<?php

declare(strict_types=1);

namespace App\Domain\Customer\Services;

use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class CustomerByResourceIdService
{
    /**
     * @param string $customerResource
     * @return Customer
     * @throws SystemFailureException
     */
    public function execute(
        string $customerResource
    ): Customer {
        try {
            // Run the query inside a database transaction
            $customer = DB::transaction(
                fn () => Customer::query()
                    ->where('resource_id', $customerResource)
                    ->first()
            );

            // Throw exception if no customer is found
            if (! $customer) {
                throw new SystemFailureException("There is no customer record found for resource id: {$customerResource}.");
            }

            // Return the customer resource if found
            return $customer;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in CustomerByResourceIdService', [
                'customer_resource' => $customerResource,
                'error_message' => $throwable->getMessage(),
                'file' => $throwable->getFile(),
                'line' => $throwable->getLine(),
                'trace' => $throwable->getTraceAsString(),
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was an error while trying to fetch the customer.',
            );
        }
    }
}
