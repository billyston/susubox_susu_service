<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\GoalGetterSusu;

use App\Domain\Shared\Exceptions\CancellationNotAllowedException;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\GoalGetterSusu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class GoalGetterSusuCancelService
{
    /**
     * @param GoalGetterSusu $goalGetterSusu
     * @return bool
     * @throws CancellationNotAllowedException
     * @throws SystemFailureException
     */
    public static function execute(
        GoalGetterSusu $goalGetterSusu,
    ): bool {
        try {
            // Get the account through the individual account
            $account = $goalGetterSusu->account;

            // Guard: prevent deleting if transactions already exist
            if ($account->transactions()->exists()) {
                throw new CancellationNotAllowedException(
                    'This account cannot be deleted because it already has transactions.'
                );
            }

            // Execute the database transaction
            return DB::transaction(function () use (
                $goalGetterSusu,
                $account
            ) {
                $goalGetterSusu->accountLocks()->delete();
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
            Log::error('Exception in GoalGetterSusuCancelService', [
                'goal_getter_susu' => $goalGetterSusu,
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
