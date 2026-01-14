<?php

declare(strict_types=1);

namespace App\Application\Transaction\Listeners;

use App\Application\Susu\Handlers\IndividualSusu\IndividualAccountDebitHandler;
use App\Application\Transaction\Interfaces\TransactionCreatedEvent;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\GroupSusu\GroupAccount;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Domain\Susu\Models\IndividualSusu\FlexySusu;
use App\Domain\Susu\Models\IndividualSusu\GoalGetterSusu;
use App\Domain\Susu\Models\IndividualSusu\IndividualAccount;
use App\Domain\Transaction\Models\Transaction;
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

        // Get the accountable (IndividualAccount / GroupAccount)
        $accountable = $transaction->account->accountable;

        // Resolve the $accountable
        match (true) {
            $accountable instanceof IndividualAccount => $this->individualAccountResolver(
                individualAccount: $accountable,
                transaction: $transaction,
            ),
            $accountable instanceof GroupAccount => $this->groupAccountResolver(
                groupAccount: $accountable,
                transaction: $transaction,
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
        Transaction $transaction
    ): void {
        // Get the susu account (type)
        $susu = $individualAccount->susu();

        // Resolve and handle the $susu type
        match (true) {
            $susu instanceof DailySusu => $this->individualAccountDebitHandler->dailySusuDispatchableHandler(transaction: $transaction),
            $susu instanceof BizSusu => $this->individualAccountDebitHandler->bizSusuDispatchableHandler(transaction: $transaction),
            $susu instanceof GoalGetterSusu => $this->individualAccountDebitHandler->goalGetterSusuDispatchableHandler(transaction: $transaction),
            $susu instanceof FlexySusu => $this->individualAccountDebitHandler->flexySusuDispatchableHandler(transaction: $transaction),

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
        Transaction $transaction
    ): void {
        // ..
    }
}
