<?php

declare(strict_types=1);

namespace App\Application\Transaction\Listeners;

use App\Application\Susu\Handlers\IndividualSusu\IndividualAccountCreditHandler;
use App\Application\Transaction\Interfaces\TransactionCreatedEvent;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Transaction\Services\TransactionByResourceIdService;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class TransactionCreditCompletedListener implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @param TransactionByResourceIdService $transactionByResourceIdService
     * @param IndividualAccountCreditHandler $individualAccountCreditHandler
     */
    public function __construct(
        private readonly TransactionByResourceIdService $transactionByResourceIdService,
        private readonly IndividualAccountCreditHandler $individualAccountCreditHandler,
    ) {
        // ..
    }

    /**
     * @param TransactionCreatedEvent $transactionCreatedEvent
     * @return void
     * @throws SystemFailureException
     */
    public function handle(
        TransactionCreatedEvent $transactionCreatedEvent
    ): void {
        // Get the transactionResourceID from the TransactionCreditCreatedEvent
        $transactionResourceID = $transactionCreatedEvent->transactionResourceId;

        // Execute the TransactionByResourceIdService and return the resource
        $transaction = $this->transactionByResourceIdService->execute(
            resourceID: $transactionResourceID,
        );

        // Get the account from $transaction
        $account = $transaction->account;

        // Resolve the susu type (scheme) and execute the handler
        match (true) {
            $account->dailySusu()->exists() => $this->individualAccountCreditHandler->dailySusuDispatchableHandler(transaction: $transaction),
            $account->bizSusu()->exists() => $this->individualAccountCreditHandler->bizSusuDispatchableHandler(transaction: $transaction),
            $account->goalGetterSusu()->exists() => $this->individualAccountCreditHandler->goalGetterSusuDispatchableHandler(transaction: $transaction),
            $account->flexySusu()->exists() => $this->individualAccountCreditHandler->flexySusuDispatchableHandler(transaction: $transaction),

//            $account->nkabomNhyiraSusu()->exists() => 'NkabomNhyiraSusu',
//            $account->dwadieboaSusu()->exists() => 'DwadieboaSusu',
//            $account->corporativeSusu()->exists() => 'CorporativeSusu',

            default => null
        };
    }
}
