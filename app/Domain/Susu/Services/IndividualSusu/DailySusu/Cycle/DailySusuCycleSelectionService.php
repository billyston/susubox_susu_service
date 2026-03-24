<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\IndividualSusu\DailySusu\Cycle;

use App\Domain\Account\Models\Account;
use App\Domain\Account\Services\AccountCycle\AccountCycleSelectAllCompletedService;
use App\Domain\Account\Services\AccountCycle\AccountCycleSelectAllWithRunningService;
use App\Domain\Account\Services\AccountCycle\AccountCycleSelectCompletedService;
use App\Domain\Shared\Enums\SettlementScopes;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Exception;
use Illuminate\Database\Eloquent\Collection;

final readonly class DailySusuCycleSelectionService
{
    /**
     * @param AccountCycleSelectCompletedService $accountCycleSelectedCompletedService
     * @param AccountCycleSelectAllCompletedService $accountCycleSelectedAllCompletedService
     * @param AccountCycleSelectAllWithRunningService $accountCycleSelectAllWithRunningService
     */
    public function __construct(
        private AccountCycleSelectCompletedService $accountCycleSelectedCompletedService,
        private AccountCycleSelectAllCompletedService $accountCycleSelectedAllCompletedService,
        private AccountCycleSelectAllWithRunningService $accountCycleSelectAllWithRunningService,
    ) {
    }

    /**
     * @param Account $account
     * @param SettlementScopes $scope
     * @param array|null $cycleResourceIDs
     * @return Collection
     * @throws Exception
     */
    public function execute(
        Account $account,
        SettlementScopes $scope,
        ?array $cycleResourceIDs = null,
    ): Collection {
        return match ($scope) {
            SettlementScopes::SELECTED_COMPLETED => $this->accountCycleSelectedCompletedService->execute(
                account: $account,
                cycleResourceIDs: $cycleResourceIDs
            ),
            SettlementScopes::ALL_COMPLETED => $this->accountCycleSelectedAllCompletedService->execute(
                account: $account,
            ),
            SettlementScopes::ALL_INCLUDING_RUNNING => $this->accountCycleSelectAllWithRunningService->execute(
                account: $account,
            ),

            default => throw new SystemFailureException(message: 'Invalid account settlement scope.'),
        };
    }
}
