<?php

declare(strict_types=1);

namespace App\Application\Susu\Jobs\DailySusu;

use App\Domain\Customer\Models\Customer;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Services\Http\Common\Jobs\Account\DailySusuActivationHttpPublishJob;
use App\Services\RabbitMQ\Jobs\Account\DailySusuActivationRabbitMQPublishJob;
use App\Services\Redis\Jobs\Account\DailySusuActivationRedisPublishJob;
use App\Services\Shared\Data\Susu\DailySusuData;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Throwable;

final class DailySusuApprovalJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly Customer $customer,
        private readonly DailySusu $dailySusu,
    ) {
        // ...
    }

    /**
     * @throws Throwable
     */
    public function handle(
        DailySusuData $dailySusuData,
    ): void {
        // Build the DailySusuData and return the array
        $data = $dailySusuData::toArray(
            dailySusu: $this->dailySusu
        );

        Bus::batch([
            new DailySusuActivationHttpPublishJob($data),
            new DailySusuActivationRabbitMQPublishJob($data),
            new DailySusuActivationRedisPublishJob($data),
        ])
            ->name('daily_susu_approval_job')
            ->allowFailures()
            ->dispatch();
    }
}
