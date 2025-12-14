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

    public function execute(
        Transaction $transaction,
        array $responseDto
    ): void {
    }
}
