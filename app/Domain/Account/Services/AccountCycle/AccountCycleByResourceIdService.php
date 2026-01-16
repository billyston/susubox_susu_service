<?php

declare(strict_types=1);

namespace App\Domain\Account\Services\AccountCycle;

use App\Domain\Account\Models\AccountCycle;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class AccountCycleByResourceIdService
{
    /**
     * @param string $accountCycleResource
     * @return AccountCycle
     * @throws SystemFailureException
     */
    public function execute(
        string $accountCycleResource
    ): AccountCycle {
        try {
            // Run the query inside a database transaction
            $accountCycle = DB::transaction(
                fn () => AccountCycle::query()
                    ->where('resource_id', $accountCycleResource)
                    ->first()
            );

            // Throw exception if no AccountCycle is found
            if (! $accountCycle) {
                throw new SystemFailureException('There is no account cycle record found for resource id: '.$accountCycleResource);
            }

            // Return the AccountCycle resource if found
            return $accountCycle;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in AccountCycleByResourceIdService', [
                'account_cycle_resource' => $accountCycleResource,
                'error_message' => $throwable->getMessage(),
                'file' => $throwable->getFile(),
                'line' => $throwable->getLine(),
                'trace' => $throwable->getTraceAsString(),
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was a system failure while fetching the account cycle record.',
            );
        }
    }
}
