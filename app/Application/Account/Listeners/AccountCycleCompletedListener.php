<?php

declare(strict_types=1);

namespace App\Application\Account\Listeners;

use App\Application\Account\Events\AccountCycleCompletedEvent;
use App\Application\Susu\Jobs\IndividualSusu\DailySusu\DailySusuAccountCycleCompletedJob;
use App\Application\Susu\Jobs\IndividualSusu\DailySusu\DailySusuAutoSettlementJob;
use App\Domain\Account\Models\AccountCycle;
use App\Domain\Account\Services\AccountCycleByResourceIdService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\GroupSusu\GroupAccount;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Domain\Susu\Models\IndividualSusu\IndividualAccount;
use App\Domain\Transaction\Models\Transaction;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;

final class AccountCycleCompletedListener implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @param AccountCycleByResourceIdService $accountCycleByResourceIdService
     */
    public function __construct(
        private readonly AccountCycleByResourceIdService $accountCycleByResourceIdService
    ) {
        //..
    }

    /**
     * @param AccountCycleCompletedEvent $accountCycleCompletedEvent
     * @return void
     * @throws SystemFailureException
     */
    public function handle(
        AccountCycleCompletedEvent $accountCycleCompletedEvent
    ): void {
        // Get the accountCycleResourceId from the AccountCycleCompletedEvent
        $accountCycleResourceId = $accountCycleCompletedEvent->accountCycleResourceId;

        // Execute the AccountCycleByResourceIdService and return the resource
        $accountCycle = $this->accountCycleByResourceIdService->execute(
            accountCycleResource: $accountCycleResourceId,
        );

        // Get the accountable (IndividualAccount / GroupAccount)
        $accountable = $accountCycle->account->accountable;

        // Resolve the $accountable
        match (true) {
            $accountable instanceof IndividualAccount => $this->individualAccountResolver(
                individualAccount: $accountable,
                accountCycle: $accountCycle,
            ),
            $accountable instanceof GroupAccount => $this->groupAccountResolver(
                groupAccount: $accountable,
                accountCycle: $accountCycle,
            ),

            default => null
        };
    }

    /**
     * @param IndividualAccount $individualAccount
     * @param Transaction $transaction
     * @return void
     */
    private function individualAccountResolver(
        IndividualAccount $individualAccount,
        AccountCycle $accountCycle
    ): void {
        // Get the susu account (type)
        $susu = $individualAccount->susu();

        // Resolve and handle the $susu type
        match (true) {
            $susu instanceof DailySusu => $this->dailySusuDispatchableHandler(accountCycle: $accountCycle),

            default => null
        };
    }

    /**
     * @param GroupAccount $groupAccount
     * @param Transaction $transaction
     * @return void
     */
    private function groupAccountResolver(
        GroupAccount $groupAccount,
        AccountCycle $accountCycle
    ): void {
        // ..
    }

    /**
     * @param Transaction $transaction
     * @return void
     */
    private function dailySusuDispatchableHandler(
        AccountCycle $accountCycle
    ): void {
        // Chain the dependable jobs
        Bus::chain([
            new DailySusuAccountCycleCompletedJob(accountCycleResourceID: $accountCycle->resource_id),
            new DailySusuAutoSettlementJob(accountCycleResourceID: $accountCycle->resource_id),
        ])->dispatch();
    }
}
