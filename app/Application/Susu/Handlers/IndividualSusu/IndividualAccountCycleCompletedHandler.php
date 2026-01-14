<?php

namespace App\Application\Susu\Handlers\IndividualSusu;

use App\Application\Susu\Jobs\IndividualSusu\DailySusu\AccountSettlement\DailySusuAutoSettlementJob;
use App\Application\Susu\Jobs\IndividualSusu\DailySusu\DailySusuAccountCycleCompletedJob;
use App\Domain\Account\Models\AccountCycle;
use Illuminate\Support\Facades\Bus;

final class IndividualAccountCycleCompletedHandler
{
    /**
     * @param AccountCycle $accountCycle
     * @return void
     */
    public function dailySusuDispatchableHandler(
        AccountCycle $accountCycle
    ): void {
        // Chain the dependable jobs
        Bus::chain([
            new DailySusuAccountCycleCompletedJob(accountCycleResourceID: $accountCycle->resource_id),
            new DailySusuAutoSettlementJob(accountCycleResourceID: $accountCycle->resource_id),
        ])->dispatch();
    }
}
