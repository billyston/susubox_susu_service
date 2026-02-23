<?php

declare(strict_types=1);

namespace App\Domain\Account\Services\AccountPause;

use App\Domain\PaymentInstruction\Models\RecurringDepositPause;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class AccountPauseByResourceIdService
{
    /**
     * @param string $accountPauseResource
     * @return RecurringDepositPause
     * @throws SystemFailureException
     */
    public function execute(
        string $accountPauseResource
    ): RecurringDepositPause {
        try {
            // Run the query inside a database transaction
            $AccountPause = DB::transaction(
                fn () => RecurringDepositPause::query()
                    ->where('resource_id', $accountPauseResource)
                    ->first()
            );

            // Throw exception if no RecurringDepositPause is found
            if (! $AccountPause) {
                throw new SystemFailureException('There is no account pause record found for resource id: '.$accountPauseResource);
            }

            // Return the RecurringDepositPause resource if found
            return $AccountPause;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in AccountPauseByResourceIdService', [
                'account_pause_resource' => $accountPauseResource,
                'error_message' => $throwable->getMessage(),
                'file' => $throwable->getFile(),
                'line' => $throwable->getLine(),
                'trace' => $throwable->getTraceAsString(),
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was a system failure while trying to fetch the account pause record.',
            );
        }
    }
}
