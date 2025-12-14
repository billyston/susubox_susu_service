<?php

declare(strict_types=1);

namespace App\Domain\Transaction\Services;

use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Transaction\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class TransactionByResourceIdService
{
    /**
     * @throws SystemFailureException
     */
    public static function execute(
        string $resource_id,
    ): Transaction {
        try {
            // Run the query inside a database transaction
            $transaction = DB::transaction(
                fn () => Transaction::query()
                    ->where('resource_id', $resource_id)
                    ->first()
            );

            // Throw exception if no record is found
            if (! $transaction) {
                throw new SystemFailureException("There is no transaction record found for resource id: {$resource_id}.");
            }

            // Return the record if found
            return $transaction;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in TransactionByResourceIdService', [
                'resource_id' => $resource_id,
                'error_message' => $throwable->getMessage(),
                'file' => $throwable->getFile(),
                'line' => $throwable->getLine(),
                'trace' => $throwable->getTraceAsString(),
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was an error while trying to fetch the transaction.',
            );
        }
    }
}
