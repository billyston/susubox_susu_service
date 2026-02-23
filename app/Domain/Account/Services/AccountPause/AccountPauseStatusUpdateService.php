<?php

declare(strict_types=1);

namespace App\Domain\Account\Services\AccountPause;

use App\Domain\PaymentInstruction\Models\RecurringDepositPause;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class AccountPauseStatusUpdateService
{
    /**
     * @param RecurringDepositPause $accountPause
     * @param string $status
     * @return RecurringDepositPause
     * @throws SystemFailureException
     */
    public static function execute(
        RecurringDepositPause $accountPause,
        string $status,
    ): RecurringDepositPause {
        try {
            // Execute the database transaction
            return DB::transaction(
                function () use (
                    $accountPause,
                    $status
                ) {
                    // Execute the update query
                    $accountPause->update(['status' => $status]);

                    // Return the account resource
                    return $accountPause->refresh();
                }
            );
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in AccountPauseStatusUpdateService', [
                'account_pause' => $accountPause,
                'status' => $status,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was a system failure while updating the account pause status.',
            );
        }
    }
}
