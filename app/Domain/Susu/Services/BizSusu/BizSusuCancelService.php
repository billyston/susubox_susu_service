<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\BizSusu;

use App\Domain\Shared\Exceptions\CancellationNotAllowedException;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class BizSusuCancelService
{
    /**
     * @param BizSusu $bizSusu
     * @return bool
     * @throws CancellationNotAllowedException
     * @throws SystemFailureException
     */
    public static function execute(
        BizSusu $bizSusu,
    ): bool {
        try {
            // Get the account through the individual account
            $account = $bizSusu->account;

            // Guard: prevent deleting if transactions already exist
            if ($account->transactions()->exists()) {
                throw new CancellationNotAllowedException(
                    'This account cannot be deleted because it already has transactions.'
                );
            }

            // Execute the database transaction
            return DB::transaction(function () use (
                $bizSusu,
                $account
            ) {
                $bizSusu->accountLocks()->delete();
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
            Log::error('Exception in BizSusuCancelService', [
                'biz_susu' => $bizSusu,
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
