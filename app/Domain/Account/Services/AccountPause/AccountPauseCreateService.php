<?php

declare(strict_types=1);

namespace App\Domain\Account\Services\AccountPause;

use App\Application\Account\DTOs\AccountPause\AccountPauseRequestDTO;
use App\Application\Shared\Helpers\Helpers;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\PaymentInstruction\Models\RecurringDepositPause;
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
     * @param Model $susuAccount
     * @param PaymentInstruction $paymentInstruction
     * @param AccountPauseRequestDTO $requestDTO
     * @return RecurringDepositPause
     * @throws SystemFailureException
     */
    public function execute(
        Model $susuAccount,
        PaymentInstruction $paymentInstruction,
        AccountPauseRequestDTO $requestDTO
    ): RecurringDepositPause {
        try {
            // Execute the database transaction
            return DB::transaction(function () use (
                $susuAccount,
                $paymentInstruction,
                $requestDTO
            ) {
                // Get the duration days
                $duration = Helpers::getDaysInDuration(
                    date: $requestDTO->duration
                )->days;

                // Create and return the AccountPayoutLock
                return $susuAccount->pauses()->create([
                    'payment_instruction_id' => $paymentInstruction->id,
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
