<?php

declare(strict_types=1);

namespace App\Domain\Customer\Services;

use App\Application\Customer\DTOs\CustomerCreateRequestDTO;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class CustomerCreateService
{
    /**
     * @param CustomerCreateRequestDTO $requestDTO
     * @return Customer
     * @throws SystemFailureException
     */
    public static function execute(
        CustomerCreateRequestDTO $requestDTO
    ): Customer {
        try {
            // Execute the database transaction
            return DB::transaction(
                function () use (
                    $requestDTO
                ) {
                    return Customer::query()->updateOrCreate([
                        'phone_number' => $requestDTO->phoneNumber,
                    ], [
                        'resource_id' => $requestDTO->resourceID,
                        'phone_number' => $requestDTO->phoneNumber,
                    ])->refresh();
                }
            );
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in CustomerCreateService', [
                'data' => $requestDTO,
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
