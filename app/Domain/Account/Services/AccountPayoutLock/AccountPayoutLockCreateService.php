<?php

declare(strict_types=1);

namespace App\Domain\Account\Services\AccountPayoutLock;

use App\Application\Account\DTOs\AccountPayoutLock\AccountPayoutLockRequestDTO;
use App\Application\Shared\Helpers\Helpers;
use App\Domain\Account\Models\Account;
use App\Domain\Account\Models\AccountPayoutLock;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class AccountPayoutLockCreateService
{
    /**
     * @throws SystemFailureException
     */
    public function execute(
        Account $account,
        AccountPayoutLockRequestDTO $requestDTO
    ): AccountPayoutLock {
        try {
            // Execute the database transaction
            return DB::transaction(function () use (
                $account,
                $requestDTO
            ) {
                // Get the duration days
                $duration = Helpers::getDaysInDuration(
                    date: $requestDTO->duration
                )->days;

                // Create and return the AccountPayoutLock
                return $account->accountPayoutLocks()->create([
                    'locked_at' => Carbon::today(),
                    'expires_at' => Helpers::getDateWithOffset(
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
            Log::error('Exception in AccountPayoutLockCreateService', [
                'account' => $account,
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
