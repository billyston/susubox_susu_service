<?php

declare(strict_types=1);

namespace App\Domain\Account\Services\AccountLock;

use App\Application\Account\DTOs\AccountLockRequestDTO;
use App\Application\Shared\Helpers\Helpers;
use App\Domain\Account\Models\AccountLock;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class AccountLockCreateService
{
    /**
     * @throws SystemFailureException
     */
    public function execute(
        Model $susuAccount,
        AccountLockRequestDTO $requestDTO
    ): AccountLock {
        try {
            // Execute the database transaction
            return DB::transaction(function () use (
                $susuAccount,
                $requestDTO
            ) {
                // Get the duration days
                $duration = Helpers::getDaysInDuration(
                    date: $requestDTO->duration
                )->days;

                // Create and return the AccountLock
                return $susuAccount->accountLocks()->create([
                    'locked_at' => Carbon::today(),
                    'unlocked_at' => Helpers::getDateWithOffset(
                        date: Carbon::today(),
                        days: $duration
                    ),
                    'accepted_terms' => true,
                    'status' => Statuses::PENDING->value,
                ]);
            });
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in AccountLockCreateService', [
                'susu' => $susuAccount,
                'request' => $requestDTO,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'A system failure occurred while trying to create the account lock.',
            );
        }
    }
}
