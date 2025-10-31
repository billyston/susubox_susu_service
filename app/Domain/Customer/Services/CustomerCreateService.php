<?php

declare(strict_types=1);

namespace App\Domain\Customer\Services;

use App\Application\Customer\DTOs\CustomerCreateDTO;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class CustomerCreateService
{
    /**
     * @throws SystemFailureException
     */
    public static function execute(
        CustomerCreateDTO $data
    ): Customer {
        try {
            // Execute the database transaction
            return DB::transaction(
                function () use (
                    $data
                ) {
                    return Customer::query()->updateOrCreate([
                        'phone_number' => $data->phone_number,
                    ], [
                        'resource_id' => $data->resource_id,
                        'phone_number' => $data->phone_number,
                    ])->refresh();
                }
            );
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in CustomerCreateService', [
                'data' => $data,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was an error while trying to create the customer.',
            );
        }
    }
}
