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

final class AccountCycleSelectCompletedService
{
    /**
     * @param Account $account
     * @param array|null $cycleResourceIDs
     * @return Collection
     * @throws SystemFailureException
     */
    public function execute(
        Account $account,
        ?array $cycleResourceIDs,
    ): Collection {
        try {
            // Run the query to fetch all selected completed cycles
            return AccountCycle::query()
                ->where('account_id', $account->id)
                ->where('status', Statuses::COMPLETED->value)
                ->whereIn('resource_id', $cycleResourceIDs ?? [])
                ->orderBy('completed_at')
                ->get();
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in AccountCycleSelectCompletedService', [
                'account' => $account,
                'cycle_resource_ids' => $cycleResourceIDs,
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
