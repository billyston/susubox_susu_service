<?php

declare(strict_types=1);

namespace App\Domain\PaymentInstruction\Services;

use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class PaymentInstructionCancelService
{
    /**
     * @param PaymentInstruction $paymentInstruction
     * @return PaymentInstruction
     * @throws SystemFailureException
     */
    public static function execute(
        PaymentInstruction $paymentInstruction,
    ): PaymentInstruction {
        try {
            // Execute the database transaction
            return DB::transaction(
                function () use (
                    $paymentInstruction,
                ) {
                    // Execute the update query
                    $paymentInstruction->update([
                        'status' => Statuses::TERMINATED->value,
                        'approval_status' => Statuses::CANCELLED->value,
                    ]);

                    // Return the account resource
                    return $paymentInstruction->refresh();
                }
            );
        } catch (
            Throwable $throwable
        ) {
            Log::error('Exception in PaymentInstructionCancelService', [
                'payment_instruction' => $paymentInstruction,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                    'code' => $throwable->getCode(),
                    'trace' => $throwable->getTraceAsString(),
                ],
            ]);

            throw new SystemFailureException(
                message: 'There was an error while trying to cancel the payment instruction.',
            );
        }
    }
}
