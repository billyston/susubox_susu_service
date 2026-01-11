<?php

declare(strict_types=1);

namespace App\Domain\PaymentInstruction\Services;

use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class PaymentInstructionStatusUpdateService
{
    /**
     * @param PaymentInstruction $paymentInstruction
     * @param string $status
     * @return PaymentInstruction
     * @throws SystemFailureException
     */
    public static function execute(
        PaymentInstruction $paymentInstruction,
        string $status,
    ): PaymentInstruction {
        try {
            // Execute the database transaction
            return DB::transaction(
                function () use (
                    $paymentInstruction,
                    $status
                ) {
                    // Execute the update query
                    $paymentInstruction->update(['status' => $status]);

                    // Return the account resource
                    return $paymentInstruction->refresh();
                }
            );
        } catch (
            Throwable $throwable
        ) {
            Log::error('Exception in PaymentInstructionStatusUpdateService', [
                'payment_instruction' => $paymentInstruction,
                'status' => $status,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            throw new SystemFailureException(
                message: 'There was a system failure while canceling the payment instruction.',
            );
        }
    }
}
