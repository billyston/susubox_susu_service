<?php

declare(strict_types=1);

namespace App\Application\Transaction\Events;

use App\Application\Transaction\Interfaces\TransactionCreatedEvent;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class TransactionDebitCreatedEvent implements TransactionCreatedEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @param string $transactionResourceId
     */
    public function __construct(
        private readonly string $transactionResourceId
    ) {
        //..
    }

    /**
     * @return string
     */
    public function transactionResourceId(
    ): string {
        return $this->transactionResourceId;
    }
}
