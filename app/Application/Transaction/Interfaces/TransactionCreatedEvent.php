<?php

declare(strict_types=1);

namespace App\Application\Transaction\Interfaces;

interface TransactionCreatedEvent
{
    public function transactionResourceId(): string;
}
