<?php

declare(strict_types=1);

namespace App\Domain\Account\Services\AccountPayoutLock;

use App\Domain\Account\Models\AccountPayoutLock;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class AccountPayoutLockStatusUpdateService
{
    /**
     * @param AccountPayoutLock $accountPayoutLock
     * @param string $status
     * @return AccountPayoutLock
     * @throws SystemFailureException
     */
    public static function execute(
        AccountPayoutLock $accountPayoutLock,
        string $status,
    ): AccountPayoutLock {
        try {
            // Execute the database transaction
            return DB::transaction(
                function () use (
                    $accountPayoutLock,
                    $status
                ) {
                    // Execute the update query
                    $accountPayoutLock->update(['status' => $status]);

                    // Return the account resource
                    return $accountPayoutLock->refresh();
                }
            );
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in AccountPayoutLockStatusUpdateService', [
                'account_payout_lock' => $accountPayoutLock,
                'status' => $status,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was a system failure while updating the account lock status.',
            );
        }
    }
}
