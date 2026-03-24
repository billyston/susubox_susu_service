<?php

declare(strict_types=1);

namespace App\Domain\PaymentInstruction\Services\RecurringDeposit;

use App\Domain\PaymentInstruction\Models\RecurringDepositPause;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class RecurringDepositPauseByResourceIdService
{
    /**
     * @param string $recurringDepositPauseResourceID
     * @return RecurringDepositPause
     * @throws SystemFailureException
     */
    public function execute(
        string $recurringDepositPauseResourceID
    ): RecurringDepositPause {
        try {
            // Run the query inside a database transaction
            $recurringDepositPause = DB::transaction(
                fn () => RecurringDepositPause::query()
                    ->where('resource_id', $recurringDepositPauseResourceID)
                    ->first()
            );

            // Throw exception if no RecurringDepositPause is found
            if (! $recurringDepositPause) {
                throw new SystemFailureException('There is no account pause record found for resource id: '.$recurringDepositPauseResourceID);
            }

            // Return the RecurringDeposit resource
            return $recurringDepositPause;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in RecurringDepositPauseByResourceIdService', [
                'recurring_deposit_pause_resource_id' => $recurringDepositPauseResourceID,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                    'code' => $throwable->getCode(),
                    'trace' => $throwable->getTraceAsString(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was a system failure while trying to fetch the account pause record.',
            );
        }
    }
}
