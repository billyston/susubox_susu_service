<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\IndividualSusu\DailySusu\AutoSettlement;

use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class DailySusuAutoSettlementToggleService
{
    /**
     * @param DailySusu $dailySusu
     * @return DailySusu
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public static function execute(
        DailySusu $dailySusu,
    ): DailySusu {
        try {
            // Execute the database transaction
            return DB::transaction(
                function () use (
                    $dailySusu,
                ) {
                    $currentStatus = (bool) $dailySusu->auto_payout;
                    $newStatus = ! $currentStatus;

                    // Update the (auto_settlement) status with the $newStatus
                    $dailySusu->update([
                        'auto_payout' => $newStatus,
                    ]);

                    // Refresh and return the DailySusu
                    return $dailySusu->refresh();
                }
            );
        } catch (
            UnauthorisedAccessException $domainException
        ) {
            throw $domainException;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in DailySusuAutoSettlementToggleService', [
                'daily_susu' => $dailySusu,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was a system failure while updating the account auto debit status.',
            );
        }
    }
}
