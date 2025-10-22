<?php

declare(strict_types=1);

namespace App\Services\Http\Common\Jobs\Transaction;

use App\Services\Http\Notification\Jobs\Transaction\NotificationServiceTransactionCreatedRequestJob;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Throwable;

final class TransactionCreatedHttpPublishJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly array $data,
    ) {
        // ...
    }

    /**
     * @throws Throwable
     */
    public function handle(
    ): void {
        Bus::batch([
            new NotificationServiceTransactionCreatedRequestJob($this->data),
        ])
            ->name('transaction_created_http_publish_job')
            ->allowFailures()
            ->dispatch();
    }
}
