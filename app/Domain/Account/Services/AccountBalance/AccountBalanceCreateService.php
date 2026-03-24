<?php

declare(strict_types=1);

namespace App\Domain\Account\Services\AccountBalance;

use App\Domain\Account\Models\Account;
use App\Domain\Account\Models\AccountBalance;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class AccountBalanceCreateService
{
    /**
     * @throws SystemFailureException
     */
    public function execute(
        Account $account,
    ): AccountBalance {
        try {
            // Execute the database transaction
            return DB::transaction(function () use (
                $account,
            ) {
                // Create the AccountBalance
                return $account->accountBalance()->create();
            });
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in AccountBalanceCreateService', [
                'account' => $account,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'A system failure occurred while trying to the account balance.',
            );
        }
    }
}
