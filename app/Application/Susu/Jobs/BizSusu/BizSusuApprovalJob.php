<?php

declare(strict_types=1);

namespace App\Application\Susu\Jobs\BizSusu;

use App\Domain\Customer\Models\Customer;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Services\Http\Common\Jobs\Account\BizSusuActivationHttpPublishJob;
use App\Services\RabbitMQ\Jobs\Account\BizSusuActivationRabbitMQPublishJob;
use App\Services\Redis\Jobs\Account\BizSusuActivationRedisPublishJob;
use App\Services\Shared\Data\Susu\BizSusuData;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Throwable;

final class BizSusuApprovalJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly Customer $customer,
        private readonly BizSusu $bizSusu,
    ) {
        // ...
    }

    /**
     * @throws Throwable
     */
    public function handle(
        BizSusuData $bizSusuData,
    ): void {
        // Build the BizSusuData and return the array
        $data = $bizSusuData::toArray(
            bizSusu: $this->bizSusu
        );

        Bus::batch([
            new BizSusuActivationHttpPublishJob($data),
            new BizSusuActivationRabbitMQPublishJob($data),
            new BizSusuActivationRedisPublishJob($data),
        ])
            ->name('daily_susu_approval_job')
            ->allowFailures()
            ->dispatch();
    }
}
