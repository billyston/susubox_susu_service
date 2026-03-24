<?php

declare(strict_types=1);

namespace App\Domain\PaymentInstruction\Services\RecurringDeposit;

use App\Domain\PaymentInstruction\Models\RecurringDeposit;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class RecurringDepositRolloverEnabledService
{
    /**
     * @param RecurringDeposit $recurringDeposit
     * @param bool $state
     * @return RecurringDeposit
     * @throws SystemFailureException
     */
    public static function execute(
        RecurringDeposit $recurringDeposit,
        bool $state,
    ): RecurringDeposit {
        try {
            // Execute the database transaction
            return DB::transaction(
                function () use (
                    $recurringDeposit,
                    $state
                ) {
                    // Execute the update query
                    $recurringDeposit->update(['rollover_enabled' => $state]);

                    // Return the account resource
                    return $recurringDeposit->refresh();
                }
            );
        } catch (
            Throwable $throwable
        ) {
            Log::error('Exception in RecurringDepositRolloverEnabledService', [
                'recurring_deposit' => $recurringDeposit,
                'state' => $state,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            throw new SystemFailureException(
                message: 'There was an error while updating the recurring deposit rollover state.',
            );
        }
    }
}
