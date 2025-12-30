<?php

declare(strict_types=1);

namespace App\Domain\Account\Services;

use App\Domain\Account\Models\AccountLock;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class AccountUnlockDueDateService
{
    /**
     * @param int $chunkSize
     * @return Collection
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
                return AccountLock::query()
                    ->where('status', Statuses::ACTIVE->value)
                    ->whereNotNull('locked_at')
                    ->where('locked_at', '<=', now())
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
