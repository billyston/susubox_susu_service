<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\IndividualSusu\DailySusu\AccountSettlement;

use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Throwable;

final class DailySusuSettlementStatusUpdateService
{
    /**
     * @param DailySusu $dailySusu
     * @param string $status
     * @return DailySusu
     * @throws SystemFailureException
     */
    public static function execute(
        DailySusu $dailySusu,
        string $status,
    ): DailySusu {
        try {
            // Execute the database transaction
            return DB::transaction(
                function () use (
                    $dailySusu,
                    $status
                ) {
                    // Execute the update query
                    $dailySusu->update(['settlement_status' => $status]);

                    // Return the account resource
                    return $dailySusu->refresh();
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
            Log::error('Exception in DailySusuSettlementStatusUpdateService', [
                'daily_susu' => $dailySusu,
                'status' => $status,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was system failure while trying to update the settlement status.',
            );
        }
    }
}
