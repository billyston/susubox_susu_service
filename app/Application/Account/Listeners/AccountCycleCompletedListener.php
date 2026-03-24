<?php

declare(strict_types=1);

namespace App\Application\Account\Listeners;

use App\Application\Account\Events\AccountCycleCompletedEvent;
use App\Application\Susu\Handlers\IndividualSusu\IndividualCycleCompletedHandler;
use App\Domain\Account\Services\AccountCycle\AccountCycleByResourceIdService;
use App\Domain\Shared\Exceptions\SystemFailureException;
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
     * @param IndividualCycleCompletedHandler $individualAccountCycleCompletedHandler
     */
    public function __construct(
        private readonly AccountCycleByResourceIdService $accountCycleByResourceIdService,
        private readonly IndividualCycleCompletedHandler $individualAccountCycleCompletedHandler
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

        // Get the account from $transaction
        $account = $accountCycle->account;

        // Resolve the susu type (scheme) and execute the handler
        match (true) {
            $account->dailySusu()->exists() => $this->individualAccountCycleCompletedHandler->dailySusuDispatchableHandler(accountCycle: $accountCycle),

            default => null
        };
    }
}
