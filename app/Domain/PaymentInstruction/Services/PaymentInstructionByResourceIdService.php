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
     * @throws SystemFailureException
     */
    public static function execute(
        string $resource_id,
    ): PaymentInstruction {
        try {
            // Run the query inside a database transaction
            $payment_instruction = DB::transaction(
                fn () => PaymentInstruction::query()
                    ->where('resource_id', $resource_id)
                    ->first()
            );

            // Throw exception if no record is found
            if (! $payment_instruction) {
                throw new SystemFailureException("There is no payment instruction record found for resource id: {$resource_id}.");
            }

            // Return the record if found
            return $payment_instruction;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in PaymentInstructionByResourceIdService', [
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
