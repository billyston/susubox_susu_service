<?php

declare(strict_types=1);

namespace App\Domain\Account\Services\AccountLock;

use App\Domain\Account\Models\AccountPayoutLock;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class AccountUnlockDueDateService
{
    /**
     * @param callable $callback
     * @param int $chunkSize
     * @return void
     * @throws SystemFailureException
     */
    public static function execute(
        callable $callback,
        int $chunkSize = 200
    ): void {
        try {
            // Execute the database transaction
            DB::transaction(function () use (
                $callback,
                $chunkSize
            ) {
                return AccountPayoutLock::query()
                    ->where('status', Statuses::ACTIVE->value)
                    ->whereNotNull('locked_at')
                    ->where('unlocked_at', '<=', now())
                    ->orderBy('id')
                    ->chunkById($chunkSize, $callback);
            });
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in AccountUnlockDueDateService', [
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was a system error while trying to fetch the unlock due dates.',
            );
        }
    }
}
