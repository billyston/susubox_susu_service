<?php

declare(strict_types=1);

namespace App\Domain\Account\Services\AccountLock;

use App\Domain\Account\Models\AccountLock;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class AccountLockByResourceIdService
{
    /**
     * @param string $accountLockResource
     * @return AccountLock
     * @throws SystemFailureException
     */
    public function execute(
        string $accountLockResource
    ): AccountLock {
        try {
            // Run the query inside a database transaction
            $accountLock = DB::transaction(
                fn () => AccountLock::query()
                    ->where('resource_id', $accountLockResource)
                    ->first()
            );

            // Throw exception if no AccountLock is found
            if (! $accountLock) {
                throw new SystemFailureException("There is no account lock record found for resource id: {$accountLockResource}.");
            }

            // Return the AccountLock resource if found
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
