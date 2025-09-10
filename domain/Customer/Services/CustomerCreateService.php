<?php

declare(strict_types=1);

namespace Domain\Customer\Services;

use App\Exceptions\Common\SystemFailureExec;
use Domain\Customer\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class CustomerCreateService
{
    /**
     * @throws SystemFailureExec
     */
    public static function execute(
        array $data
    ): void {
        try {
            // Execute the database transaction
            DB::transaction(
                function () use (
                    $data
                ) {
                    return Customer::updateOrCreate([
                        'phone_number' => $data['phone_number'],
                    ], [
                        'resource_id' => $data['resource_id'],
                        'phone_number' => $data['phone_number'],
                    ])->refresh();
                }
            );
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in CustomerCreateService', [
                'request' => $data,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureExec
            throw new SystemFailureExec;
        }
    }
}
