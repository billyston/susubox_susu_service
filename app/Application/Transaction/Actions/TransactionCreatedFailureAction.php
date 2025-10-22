<?php

declare(strict_types=1);

namespace App\Application\Transaction\Actions;

use App\Domain\Transaction\Models\Transaction;
use App\Services\Shared\Jobs\Transaction\TransactionCreatedPublishJob;

final class TransactionCreatedFailureAction
{
    public function __construct(
    ) {
        // ..
    }

    public function execute(
        Transaction $transaction,
    ): void {
        // Dispatch the TransactionCreatedPublishJob (asynchronously)
        TransactionCreatedPublishJob::dispatch(
            transaction: $transaction
        );
    }
}
