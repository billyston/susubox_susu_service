<?php

declare(strict_types=1);

namespace App\Application\Susu\Jobs\GoalGetterSusu;

use App\Domain\Customer\Models\Customer;
use App\Domain\Susu\Models\IndividualSusu\GoalGetterSusu;
use App\Services\Http\Common\Jobs\Account\GoalGetterSusuActivationHttpPublishJob;
use App\Services\RabbitMQ\Jobs\Account\GoalGetterSusuActivationRabbitMQPublishJob;
use App\Services\Redis\Jobs\Account\GoalGetterSusuActivationRedisPublishJob;
use App\Services\Shared\Data\Susu\GoalGetterSusuData;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Throwable;

final class GoalGetterSusuApprovalJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly Customer $customer,
        private readonly GoalGetterSusu $goalGetterSusu,
    ) {
        // ...
    }

    /**
     * @throws Throwable
     */
    public function handle(
        GoalGetterSusuData $getterSusuData,
    ): void {
        // Build the GoalGetterSusuData and return the array
        $data = $getterSusuData::toArray(
            goalGetterSusu: $this->goalGetterSusu
        );

        Bus::batch([
            new GoalGetterSusuActivationHttpPublishJob($data),
            new GoalGetterSusuActivationRabbitMQPublishJob($data),
            new GoalGetterSusuActivationRedisPublishJob($data),
        ])
            ->name('goal_getter_susu_approval_job')
            ->allowFailures()
            ->dispatch();
    }
}
