<?php

declare(strict_types=1);

namespace App\Domain\PaymentInstruction\Services\RecurringDeposit;

use App\Domain\PaymentInstruction\Models\RecurringDeposit;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class RecurringDepositByResourceIdService
{
    /**
     * @param string $recurringDepositResourceID
     * @return RecurringDeposit
     * @throws SystemFailureException
     */
    public function execute(
        string $recurringDepositResourceID
    ): RecurringDeposit {
        try {
            // Run the query inside a database transaction
            $recurringDeposit = DB::transaction(
                fn () => RecurringDeposit::query()
                    ->where('resource_id', $recurringDepositResourceID)
                    ->first()
            );

            // Throw exception if no RecurringDepositPause is found
            if (! $recurringDeposit) {
                throw new SystemFailureException('There is no account pause record found for resource id: '.$recurringDepositResourceID);
            }

            // Return the RecurringDeposit resource
            return $recurringDeposit;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in RecurringDepositCreateService', [
                'recurring_deposit_resource_id' => $recurringDepositResourceID,
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
