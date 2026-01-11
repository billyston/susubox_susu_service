<?php

declare(strict_types=1);

namespace App\Application\Transaction\Actions;

use App\Domain\Transaction\Models\Transaction;

final class TransactionCreatedFailureAction
{
    public function __construct(
    ) {
        // ..
    }

    /**
     * @param Transaction $transaction
     * @return void
     */
    public function execute(
        Transaction $transaction,
    ): void {
    }
}
