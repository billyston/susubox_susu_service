<?php

declare(strict_types=1);

namespace App\Application\Transaction\Listeners;

use App\Application\Susu\Handlers\IndividualSusu\IndividualAccountDebitHandler;
use App\Application\Transaction\Interfaces\TransactionCreatedEvent;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Transaction\Services\TransactionByResourceIdService;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class TransactionDebitCompletedListener implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @param TransactionByResourceIdService $transactionByResourceIdService
     * @param IndividualAccountDebitHandler $individualAccountDebitHandler
     */
    public function __construct(
        private readonly TransactionByResourceIdService $transactionByResourceIdService,
        private readonly IndividualAccountDebitHandler $individualAccountDebitHandler,
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
            $account->dailySusu()->exists() => $this->individualAccountDebitHandler->dailySusuDispatchableHandler(transaction: $transaction),
            $account->bizSusu()->exists() => $this->individualAccountDebitHandler->bizSusuDispatchableHandler(transaction: $transaction),
            $account->goalGetterSusu()->exists() => $this->individualAccountDebitHandler->goalGetterSusuDispatchableHandler(transaction: $transaction),
            $account->flexySusu()->exists() => $this->individualAccountDebitHandler->flexySusuDispatchableHandler(transaction: $transaction),

            $account->nkabomNhyiraSusu()->exists() => 'NkabomNhyiraSusu',
            $account->dwadieboaSusu()->exists() => 'DwadieboaSusu',
            $account->corporativeSusu()->exists() => 'CorporativeSusu',

            default => null
        };
    }
}
