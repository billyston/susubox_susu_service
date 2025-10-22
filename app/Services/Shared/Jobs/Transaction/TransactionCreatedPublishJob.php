<?php

declare(strict_types=1);

namespace App\Services\Shared\Jobs\Transaction;

use App\Domain\Transaction\Models\Transaction;
use App\Services\Http\Common\Jobs\Transaction\TransactionCreatedHttpPublishJob;
use App\Services\RabbitMQ\Jobs\Transaction\TransactionCreatedRabbitMQPublishJob;
use App\Services\Redis\Jobs\Transaction\TransactionCreatedRedisPublishJob;
use App\Services\Shared\Data\Transactions\TransactionData;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Throwable;

final class TransactionCreatedPublishJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly Transaction $transaction,
    ) {
    }

    /**
     * @throws Throwable
     */
    public function handle(
        TransactionData $transactionData,
    ): void {
        // Build the DailySusuData and return the array
        $data = $transactionData::toArray(
            transaction: $this->transaction
        );

        Bus::batch([
            new TransactionCreatedHttpPublishJob($data),
            new TransactionCreatedRabbitMQPublishJob($data),
            new TransactionCreatedRedisPublishJob($data),
        ])
            ->name('transaction_created_publish_job')
            ->allowFailures()
            ->dispatch();
    }
}
