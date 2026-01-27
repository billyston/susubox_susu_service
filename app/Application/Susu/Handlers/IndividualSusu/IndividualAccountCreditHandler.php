<?php

namespace App\Application\Susu\Handlers\IndividualSusu;

use App\Application\Susu\Jobs\IndividualSusu\DailySusu\Cycle\DailySusuCycleCreateJob;
use App\Domain\Transaction\Models\Transaction;
use Illuminate\Support\Facades\Bus;

final class IndividualAccountCreditHandler
{
    /**
     * @param Transaction $transaction
     * @return void
     */
    public function dailySusuDispatchableHandler(
        Transaction $transaction
    ): void {
        // Chain the dependable jobs
        Bus::chain([
            new DailySusuCycleCreateJob(resourceID: $transaction->resource_id),
        ])->dispatch();
    }

    /**
     * @param Transaction $transaction
     * @return void
     */
    public function bizSusuDispatchableHandler(
        Transaction $transaction
    ): void {
        // Chain the dependable jobs
        Bus::chain([
        ])->dispatch();
    }

    /**
     * @param Transaction $transaction
     * @return void
     */
    public function goalGetterSusuDispatchableHandler(
        Transaction $transaction
    ): void {
        // Chain the dependable jobs
        Bus::chain([
        ])->dispatch();
    }

    /**
     * @param Transaction $transaction
     * @return void
     */
    public function flexySusuDispatchableHandler(
        Transaction $transaction
    ): void {
        // Chain the dependable jobs
        Bus::chain([
        ])->dispatch();
    }
}
