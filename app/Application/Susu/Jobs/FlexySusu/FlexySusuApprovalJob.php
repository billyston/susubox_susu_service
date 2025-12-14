<?php

declare(strict_types=1);

namespace App\Application\Susu\Jobs\FlexySusu;

use App\Domain\Customer\Models\Customer;
use App\Domain\Susu\Models\IndividualSusu\FlexySusu;
use App\Services\Http\Common\Jobs\Account\FlexySusuActivationHttpPublishJob;
use App\Services\RabbitMQ\Jobs\Account\FlexySusuActivationRabbitMQPublishJob;
use App\Services\Redis\Jobs\Account\FlexySusuActivationRedisPublishJob;
use App\Services\Shared\Data\Susu\FlexySusuData;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Throwable;

final class FlexySusuApprovalJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly Customer $customer,
        private readonly FlexySusu $flexySusu,
    ) {
        // ...
    }

    /**
     * @throws Throwable
     */
    public function handle(
        FlexySusuData $flexySusuData,
    ): void {
        // Build the FlexySusuData and return the array
        $data = $flexySusuData::toArray(
            flexySusu: $this->flexySusu
        );

        Bus::batch([
            new FlexySusuActivationHttpPublishJob($data),
            new FlexySusuActivationRabbitMQPublishJob($data),
            new FlexySusuActivationRedisPublishJob($data),
        ])
            ->name('flexy_susu_approval_job')
            ->allowFailures()
            ->dispatch();
    }
}
