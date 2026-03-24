<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\IndividualSusu\DailySusu\Settlement;

use App\Domain\Account\Models\AccountCycle;
use App\Domain\PaymentInstruction\Models\Settlement;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class DailySusuSettlementCompletedService
{
    /**
     * @param Settlement $settlement
     * @return Settlement
     * @throws SystemFailureException
     */
    public static function execute(
        Settlement $settlement,
    ): Settlement {
        try {
            // Execute the database transaction
            return DB::transaction(
                function () use (
                    $settlement,
                ) {
                    // Update (mark) the status as (completed)
                    $settlement->update([
                        'status' => Statuses::COMPLETED->value,
                        'completed_at' => now(),
                    ]);

                    // Fetch all account cycles linked to this settlement
                    $settlement->accountCycles()
                        ->whereNull('settled_at')
                        ->each(function (AccountCycle $cycle) {
                            $cycle->update([
                                'settled_at' => now(),
                                'status' => Statuses::SETTLED->value,
                            ]);
                        });

                    // Return the Settlement
                    return $settlement->refresh();
                }
            );
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in DailySusuSettlementCompletedService', [
                'settlement' => $settlement,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was system failure while trying to complete the settlement.',
            );
        }
    }
}
