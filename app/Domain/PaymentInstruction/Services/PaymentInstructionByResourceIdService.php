<?php

declare(strict_types=1);

namespace App\Domain\PaymentInstruction\Services;

use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class PaymentInstructionByResourceIdService
{
    /**
     * @param string $resourceID
     * @return PaymentInstruction
     * @throws SystemFailureException
     */
    public static function execute(
        string $resourceID,
    ): PaymentInstruction {
        try {
            // Run the query inside a database transaction
            $paymentInstruction = DB::transaction(
                fn () => PaymentInstruction::query()
                    ->where('resource_id', $resourceID)
                    ->first()
            );

            // Throw exception if no record is found
            if (! $paymentInstruction) {
                throw new SystemFailureException("There is no payment instruction record found for resource id: {$resourceID}.");
            }

            // Return the record if found
            return $paymentInstruction;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in PaymentInstructionByResourceIdService', [
                'resource_id' => $resourceID,
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
