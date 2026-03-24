<?php

declare(strict_types=1);

namespace App\Domain\PaymentInstruction\Services\RecurringDeposit;

use App\Domain\PaymentInstruction\Models\RecurringDepositPause;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class RecurringDepositPauseStatusUpdateService
{
    /**
     * @param RecurringDepositPause $recurringDepositPause
     * @param string $status
     * @return RecurringDepositPause
     * @throws SystemFailureException
     */
    public static function execute(
        RecurringDepositPause $recurringDepositPause,
        string $status,
    ): RecurringDepositPause {
        try {
            // Execute the database transaction
            return DB::transaction(
                function () use (
                    $recurringDepositPause,
                    $status
                ) {
                    // Execute the update query
                    $recurringDepositPause->update(['status' => $status]);

                    // Return the account resource
                    return $recurringDepositPause->refresh();
                }
            );
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in RecurringDepositPauseStatusUpdateService', [
                'recurring_deposit_pause' => $recurringDepositPause,
                'status' => $status,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was a system failure while updating the recurring deposit pause status.',
            );
        }
    }
}
