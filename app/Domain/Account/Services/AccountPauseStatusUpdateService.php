<?php

declare(strict_types=1);

namespace App\Domain\Account\Services;

use App\Domain\Account\Models\AccountPause;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Throwable;

final class AccountPauseStatusUpdateService
{
    /**
     * @param AccountPause $accountPause
     * @param string $status
     * @return AccountPause
     * @throws SystemFailureException
     */
    public static function execute(
        AccountPause $accountPause,
        string $status,
    ): AccountPause {
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
