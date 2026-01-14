<?php

declare(strict_types=1);

namespace App\Application\Account\Listeners;

use App\Application\Account\Events\AccountCycleCompletedEvent;
use App\Application\Susu\Handlers\IndividualSusu\IndividualAccountCycleCompletedHandler;
use App\Domain\Account\Models\AccountCycle;
use App\Domain\Account\Services\AccountCycleByResourceIdService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\GroupSusu\GroupAccount;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Domain\Susu\Models\IndividualSusu\IndividualAccount;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class AccountCycleCompletedListener implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @param AccountCycleByResourceIdService $accountCycleByResourceIdService
     * @param IndividualAccountCycleCompletedHandler $individualAccountCycleCompletedHandler
     */
    public function __construct(
        private readonly AccountCycleByResourceIdService $accountCycleByResourceIdService,
        private readonly IndividualAccountCycleCompletedHandler $individualAccountCycleCompletedHandler
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
     * @param AccountCycle $accountCycle
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
            $susu instanceof DailySusu => $this->individualAccountCycleCompletedHandler->dailySusuDispatchableHandler(accountCycle: $accountCycle),

            default => null
        };
    }

    /**
     * @param GroupAccount $groupAccount
     * @param AccountCycle $accountCycle
     * @return void
     */
    private function groupAccountResolver(
        GroupAccount $groupAccount,
        AccountCycle $accountCycle
    ): void {
        // ..
    }
}
