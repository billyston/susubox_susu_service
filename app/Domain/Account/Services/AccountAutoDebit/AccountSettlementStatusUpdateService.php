<?php

declare(strict_types=1);

namespace App\Domain\Account\Services\AccountAutoDebit;

use App\Domain\Account\Models\AccountSettlement;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Throwable;

final class AccountSettlementStatusUpdateService
{
    /**
     * @param AccountSettlement $accountSettlement
     * @param string $status
     * @return AccountSettlement
     * @throws SystemFailureException
     */
    public static function execute(
        AccountSettlement $accountSettlement,
        string $status,
    ): AccountSettlement {
        try {
            // Execute the database transaction
            return DB::transaction(
                function () use (
                    $accountSettlement,
                    $status
                ) {
                    // Execute the update query
                    $accountSettlement->update(['status' => $status]);

                    // Return the account resource
                    return $accountSettlement->refresh();
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
            Log::error('Exception in AccountSettlementStatusUpdateService', [
                'account_settlement' => $accountSettlement,
                'status' => $status,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was a system failure while updating the account settlement status.',
            );
        }
    }
}
