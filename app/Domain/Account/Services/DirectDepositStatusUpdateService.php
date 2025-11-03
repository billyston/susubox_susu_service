<?php

declare(strict_types=1);

namespace App\Domain\Account\Services;

use App\Domain\Account\Models\DirectDeposit;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Transaction\Enums\TransactionStatus;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Throwable;

final class DirectDepositStatusUpdateService
{
    /**
     * @throws SystemFailureException
     */
    public static function execute(
        DirectDeposit $direct_deposit,
        string $status,
    ): bool {
        try {
            // Execute the database transaction
            return DB::transaction(
                function () use (
                    $direct_deposit,
                    $status
                ) {
                    // Validate status
                    if (! in_array($status, TransactionStatus::allowed(), true)) {
                        throw new InvalidArgumentException("Invalid account status: {$status}");
                    }

                    // Execute the update query
                    return $direct_deposit->update(['status' => $status]);
                }
            );
        } catch (
            InvalidArgumentException $invalidArgumentException
        ) {
            throw $invalidArgumentException;
        } catch (
            QueryException $queryException
        ) {
            throw $queryException;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in DirectDepositStatusUpdateService', [
                'direct_deposit' => $direct_deposit,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was an error while cancelling the direct deposit process.',
            );
        }
    }
}
