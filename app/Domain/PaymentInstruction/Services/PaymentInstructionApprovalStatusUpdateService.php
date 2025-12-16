<?php

declare(strict_types=1);

namespace App\Domain\PaymentInstruction\Services;

use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Throwable;

final class PaymentInstructionApprovalStatusUpdateService
{
    /**
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
                    // Validate status
                    if (! in_array($status, Statuses::allowed(), true)) {
                        throw new InvalidArgumentException("Invalid account status: {$status}");
                    }

                    // Execute the update query
                    $paymentInstruction->update(['approval_status' => $status]);

                    // Return the account resource
                    return $paymentInstruction->refresh();
                }
            );
        } catch (
            Throwable $throwable
        ) {
            Log::error('Exception in PaymentInstructionStatusUpdateService', [
                'paymentInstruction' => $paymentInstruction,
                'status' => $status,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            throw new SystemFailureException(
                message: 'There was an error while trying to cancel the payment instruction.',
            );
        }
    }
}
