<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\IndividualSusu\DailySusu\AccountSettlement;

use App\Domain\Account\Models\AccountCycle;
use App\Domain\Account\Models\AccountSettlement;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Throwable;

final class DailySusuSettlementCompletedService
{
    /**
     * @param AccountSettlement $accountSettlement
     * @return AccountSettlement
     * @throws SystemFailureException
     */
    public static function execute(
        AccountSettlement $accountSettlement,
    ): AccountSettlement {
        try {
            // Execute the database transaction
            return DB::transaction(
                function () use (
                    $accountSettlement,
                ) {
                    // Update (mark) the status as (completed)
                    $accountSettlement->update([
                        'status' => Statuses::COMPLETED->value,
                        'completed_at' => now(),
                    ]);

                    // Fetch all account cycles linked to this settlement
                    $accountSettlement->cycles()
                        ->whereNull('settled_at')
                        ->each(function (AccountCycle $cycle) {
                            $cycle->update([
                                'settled_at' => now(),
                                'status' => Statuses::SETTLED->value,
                            ]);
                        });

                    // Return the AccountSettlement
                    return $accountSettlement->refresh();
                }
            );
        } catch (
            InvalidArgumentException $invalidArgumentException
        ) {
            throw $invalidArgumentException;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in DailySusuSettlementCompletedService', [
                'account_settlement' => $accountSettlement,
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
