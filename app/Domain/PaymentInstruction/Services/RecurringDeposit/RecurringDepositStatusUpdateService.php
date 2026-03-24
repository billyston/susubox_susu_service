<?php

declare(strict_types=1);

namespace App\Domain\PaymentInstruction\Services\RecurringDeposit;

use App\Domain\PaymentInstruction\Models\RecurringDeposit;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class RecurringDepositStatusUpdateService
{
    /**
     * @param RecurringDeposit $recurringDeposit
     * @param string $status
     * @return RecurringDeposit
     * @throws SystemFailureException
     */
    public static function execute(
        RecurringDeposit $recurringDeposit,
        string $status,
    ): RecurringDeposit {
        try {
            // Execute the database transaction
            return DB::transaction(
                function () use (
                    $recurringDeposit,
                    $status
                ) {
                    // Execute the update query
                    $recurringDeposit->update(['status' => $status]);

                    // Return the account resource
                    return $recurringDeposit->refresh();
                }
            );
        } catch (
            Throwable $throwable
        ) {
            Log::error('Exception in RecurringDepositStatusUpdateService', [
                'recurring_deposit' => $recurringDeposit,
                'status' => $status,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            throw new SystemFailureException(
                message: 'There was an error while updating the recurring debit status.',
            );
        }
    }
}
