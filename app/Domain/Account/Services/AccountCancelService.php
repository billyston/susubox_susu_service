<?php

declare(strict_types=1);

namespace App\Domain\Account\Services;

use \App\Domain\Shared\Enums\Statuses;
use App\Domain\Account\Models\Account;
use App\Domain\Shared\Exceptions\CancellationNotAllowedException;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class AccountCancelService
{
    /**
     * @throws SystemFailureException
     * @throws CancellationNotAllowedException
     */
    public static function execute(
        Account $account,
    ): bool {
        try {
            // Guard clause: ensure account is cancellable
            if (in_array($account->status, [Statuses::ACTIVE->value, Statuses::CLOSED->value])) {
                throw new CancellationNotAllowedException(
                    'This account cannot be cancelled in its current state.'
                );
            }

            // Guard: prevent deleting if transactions already exist
            if ($account->transactions()->exists()) {
                throw new CancellationNotAllowedException(
                    'This account cannot be deleted because it already has transactions.'
                );
            }

            // Execute the database transaction
            return DB::transaction(function () use ($account) {
                $accountable = $account->accountable;

                // Delete Account first
                $accountDeleted = $account->delete();

                if ($accountDeleted && $accountable) {
                    $accountable->delete();
                }

                return $accountDeleted;
            });
        } catch (
            CancellationNotAllowedException $cancellationNotAllowedException
        ) {
            throw $cancellationNotAllowedException;
        } catch (
            ModelNotFoundException $modelNotFoundException
        ) {
            throw $modelNotFoundException;
        } catch (
            QueryException $queryException
        ) {
            throw $queryException;
        } catch (
            Throwable $throwable
        ) {
            Log::critical('Exception in AccountCancelService', [
                'account' => $account,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            throw new SystemFailureException(
                message: 'A system failure occurred while cancelling the account.'
            );
        }
    }
}
