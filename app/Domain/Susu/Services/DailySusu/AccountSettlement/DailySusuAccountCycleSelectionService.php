<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\DailySusu\AccountSettlement;

use App\Domain\Account\Models\Account;
use App\Domain\Account\Models\AccountCycle;
use App\Domain\Shared\Enums\SettlementScopes;
use App\Domain\Shared\Enums\Statuses;
use Illuminate\Database\Eloquent\Collection;

final class DailySusuAccountCycleSelectionService
{
    /**
     * @param Account $account
     * @param SettlementScopes $scope
     * @param array|null $cycleResourceIDs
     * @return Collection
     */
    public function execute(
        Account $account,
        SettlementScopes $scope,
        ?array $cycleResourceIDs = null,
    ): Collection {
        return match ($scope) {
            SettlementScopes::SELECTED_COMPLETED => $this->selectSelectedCompletedCycles(
                account: $account,
                cycleResourceIDs: $cycleResourceIDs
            ),
            SettlementScopes::ALL_COMPLETED => $this->selectAllCompletedCycles(
                account: $account,
            ),
            SettlementScopes::ALL_INCLUDING_RUNNING => $this->selectAllIncludingRunningCycle(
                account: $account,
            ),
        };
    }

    /**
     * @param Account $account
     * @param array|null $cycleResourceIDs
     * @return Collection
     */
    private function selectSelectedCompletedCycles(
        Account $account,
        ?array $cycleResourceIDs,
    ): Collection {
        return AccountCycle::query()
            ->where('account_id', $account->id)
            ->where('status', Statuses::COMPLETED->value)
            ->whereIn('resource_id', $cycleResourceIDs ?? [])
            ->orderBy('completed_at')
            ->get();
    }

    /**
     * @param Account $account
     * @return Collection
     */
    private function selectAllCompletedCycles(
        Account $account,
    ): Collection {
        return AccountCycle::query()
            ->where('account_id', $account->id)
            ->where('status', Statuses::COMPLETED->value)
            ->orderBy('completed_at')
            ->get();
    }

    /**
     * @param Account $account
     * @return Collection
     */
    private function selectAllIncludingRunningCycle(
        Account $account,
    ): Collection {
        // Get all AllCompletedCycles
        $completedCycles = $this->selectAllCompletedCycles(
            account: $account
        );

        // Get a current running cycle
        $runningCycle = AccountCycle::query()
            ->where('account_id', $account->id)
            ->where('status', Statuses::ACTIVE->value)
            ->orderByDesc('started_at')
            ->first();

        return $runningCycle
            ? $completedCycles->push($runningCycle)
            : $completedCycles;
    }
}
