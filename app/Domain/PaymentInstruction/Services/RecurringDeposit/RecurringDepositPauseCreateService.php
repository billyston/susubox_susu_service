<?php

declare(strict_types=1);

namespace App\Domain\PaymentInstruction\Services\RecurringDeposit;

use App\Application\PaymentInstruction\DTOs\RecurringDeposit\RecurringDepositPauseRequestDTO;
use App\Application\Shared\Helpers\Helpers;
use App\Domain\PaymentInstruction\Models\RecurringDeposit;
use App\Domain\PaymentInstruction\Models\RecurringDepositPause;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class RecurringDepositPauseCreateService
{
    /**
     * @param RecurringDeposit $recurringDeposit
     * @param RecurringDepositPauseRequestDTO $requestDTO
     * @return RecurringDepositPause
     * @throws SystemFailureException
     */
    public function execute(
        RecurringDeposit $recurringDeposit,
        RecurringDepositPauseRequestDTO $requestDTO
    ): RecurringDepositPause {
        try {
            // Execute the database transaction
            return DB::transaction(function () use (
                $recurringDeposit,
                $requestDTO
            ) {
                // Get the duration days
                $duration = Helpers::getDaysInDuration(
                    date: $requestDTO->duration
                )->days;

                // Create and return the AccountPayoutLock
                return RecurringDepositPause::create([
                    'recurring_deposit_id' => $recurringDeposit->id,
                    'paused_at' => Carbon::today(),
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
            Log::error('Exception in RecurringDepositPauseCreateService', [
                'recurring_deposit' => $recurringDeposit,
                'request_dto' => $requestDTO,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'A system failure occurred while trying to create the recurring deposit pause.',
            );
        }
    }
}
