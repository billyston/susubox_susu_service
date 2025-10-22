<?php

declare(strict_types=1);

namespace App\Services\Http\Common\Jobs\Account;

use App\Services\Http\Payment\Jobs\PaymentServiceDirectDebitRequestJob;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Throwable;

final class FlexySusuActivationHttpPublishJob implements ShouldQueue
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
            new PaymentServiceDirectDebitRequestJob($this->data),
        ])
            ->name('linked-wallet-redis-publish')
            ->allowFailures()
            ->dispatch();
    }
}
