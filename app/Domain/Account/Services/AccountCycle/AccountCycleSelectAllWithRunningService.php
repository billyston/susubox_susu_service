<?php

declare(strict_types=1);

namespace App\Domain\Account\Services\AccountCycle;

use App\Domain\Account\Models\Account;
use App\Domain\Account\Models\AccountCycle;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Throwable;

final class AccountCycleSelectAllWithRunningService
{
    /**
     * @param Account $account
     * @return Collection
     * @throws SystemFailureException
     */
    public function execute(
        Account $account,
    ): Collection {
        try {
            // Run the query to fetch all cycles including running
            return AccountCycle::query()
                ->where('account_id', $account->id)
                ->whereIn('status', [Statuses::COMPLETED->value, Statuses::ACTIVE->value])
                ->orderBy('completed_at')
                ->get();
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in AccountCycleSelectAllWithRunningService', [
                'account' => $account,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                    'trace' => $throwable->getTraceAsString(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was a system failure while fetching the account cycle record.',
            );
        }
    }
}
