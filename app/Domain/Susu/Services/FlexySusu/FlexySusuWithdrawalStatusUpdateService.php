<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\FlexySusu;

use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\FlexySusu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Throwable;

final class FlexySusuWithdrawalStatusUpdateService
{
    /**
     * @param FlexySusu $flexySusu
     * @param string $status
     * @return FlexySusu
     * @throws SystemFailureException
     */
    public static function execute(
        FlexySusu $flexySusu,
        string $status,
    ): FlexySusu {
        try {
            // Execute the database transaction
            return DB::transaction(
                function () use (
                    $flexySusu,
                    $status
                ) {
                    // Execute the update query
                    $flexySusu->update(['withdrawal_status' => $status]);

                    // Return the account resource
                    return $flexySusu->refresh();
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
            Log::error('Exception in FlexySusuWithdrawalStatusUpdateService', [
                'flexy_susu' => $flexySusu,
                'status' => $status,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was system failure while trying to update the withdrawal status.',
            );
        }
    }
}
