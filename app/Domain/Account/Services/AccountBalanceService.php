<?php

declare(strict_types=1);

namespace App\Domain\Account\Services;

use App\Domain\Account\Models\Account;
use App\Domain\Account\Models\AccountBalance;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Support\Facades\Log;
use Throwable;

final class AccountBalanceService
{
    /**
     * @throws SystemFailureException
     */
    public static function execute(
        Account $account,
    ): AccountBalance {
        try {
            // Execute the database transaction
            return $account->accountBalance()
                ->firstOrFail();
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in AccountBalanceService', [
                'account' => $account,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was an error while trying to process account balance.',
            );
        }
    }
}
