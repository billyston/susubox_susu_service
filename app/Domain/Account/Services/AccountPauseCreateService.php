<?php

declare(strict_types=1);

namespace App\Domain\Account\Services;

use App\Application\Account\DTOs\AccountPauseRequestDTO;
use App\Application\Shared\Helpers\Helpers;
use App\Domain\Account\Models\AccountLock;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class AccountPauseCreateService
{
    /**
     * @throws SystemFailureException
     */
    public function execute(
        Model $susuAccount,
        AccountPauseRequestDTO $requestDTO
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
                return $susuAccount->accountPauses()->create([
                    'paused_at' => Carbon::today(),
                    'resumed_at' => Helpers::getDateWithOffset(
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
            Log::error('Exception in AccountPauseCreateService', [
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
                message: 'A system failure occurred while trying to create the account pause.',
            );
        }
    }
}
