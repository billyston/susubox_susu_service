<?php

declare(strict_types=1);

namespace App\Domain\Account\Services\AccountLock;

use App\Domain\Account\Models\AccountPayoutLock;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Throwable;

final class AccountLockStatusUpdateService
{
    /**
     * @param AccountPayoutLock $accountLock
     * @param string $status
     * @return AccountPayoutLock
     * @throws SystemFailureException
     */
    public static function execute(
        AccountPayoutLock $accountLock,
        string $status,
    ): AccountPayoutLock {
        try {
            // Execute the database transaction
            return DB::transaction(
                function () use (
                    $accountLock,
                    $status
                ) {
                    // Execute the update query
                    $accountLock->update(['status' => $status]);

                    // Return the account resource
                    return $accountLock->refresh();
                }
            );
        } catch (
            InvalidArgumentException $invalidArgumentException
        ) {
            throw $invalidArgumentException;
        } catch (
            QueryException $queryException
        ) {
            throw $queryException;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in AccountLockStatusUpdateService', [
                'account_lock' => $accountLock,
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
