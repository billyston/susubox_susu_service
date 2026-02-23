<?php

declare(strict_types=1);

namespace App\Domain\Account\Services\AccountLock;

use App\Domain\Account\Models\AccountPayoutLock;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class AccountLockByResourceIdService
{
    /**
     * @param string $accountLockResource
     * @return AccountPayoutLock
     * @throws SystemFailureException
     */
    public function execute(
        string $accountLockResource
    ): AccountPayoutLock {
        try {
            // Run the query inside a database transaction
            $accountLock = DB::transaction(
                fn () => AccountPayoutLock::query()
                    ->where('resource_id', $accountLockResource)
                    ->first()
            );

            // Throw exception if no AccountPayoutLock is found
            if (! $accountLock) {
                throw new SystemFailureException("There is no account lock record found for resource id: {$accountLockResource}.");
            }

            // Return the AccountPayoutLock resource if found
            return $accountLock;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in AccountLockByResourceIdService', [
                'account_lock_resource' => $accountLockResource,
                'error_message' => $throwable->getMessage(),
                'file' => $throwable->getFile(),
                'line' => $throwable->getLine(),
                'trace' => $throwable->getTraceAsString(),
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was an error while trying to fetch the account lock record.',
            );
        }
    }
}
