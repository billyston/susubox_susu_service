<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\IndividualSusu\DailySusu\Account;

use App\Domain\Shared\Exceptions\CancellationNotAllowedException;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class DailySusuCancelService
{
    /**
     * @param DailySusu $dailySusu
     * @return bool
     * @throws CancellationNotAllowedException
     * @throws SystemFailureException
     */
    public static function execute(
        DailySusu $dailySusu,
    ): bool {
        try {
            // Get the account through the individual account
            $account = $dailySusu->account;

            // Guard: prevent deleting if transactions already exist
            if ($account->transactions()->exists()) {
                throw new CancellationNotAllowedException(
                    'This account cannot be deleted because it already has transactions.'
                );
            }

            // Execute the database transaction
            return DB::transaction(function () use (
                $dailySusu,
                $account
            ) {
                $dailySusu->accountLocks()->delete();
                return $account->delete();
            });
        } catch (
            CancellationNotAllowedException $cancellationNotAllowedException
        ) {
            throw $cancellationNotAllowedException;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in DailySusuCancelService', [
                'daily_susu' => $dailySusu,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was a system error while trying to cancel the create account process.',
            );
        }
    }
}
