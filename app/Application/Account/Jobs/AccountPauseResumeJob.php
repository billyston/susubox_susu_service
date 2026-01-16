<?php

declare(strict_types=1);

namespace App\Application\Account\Jobs;

use App\Domain\Account\Services\AccountPause\AccountPauseByResourceIdService;
use App\Domain\Account\Services\AccountPause\AccountPauseStatusUpdateService;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class AccountPauseResumeJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly string $resourceID
    ) {
        // ...
    }

    /**
     * @param AccountPauseByResourceIdService $accountPauseByResourceIdService
     * @param AccountPauseStatusUpdateService $accountPauseStatusUpdateService
     * @return void
     * @throws SystemFailureException
     */
    public function handle(
        AccountPauseByResourceIdService $accountPauseByResourceIdService,
        AccountPauseStatusUpdateService $accountPauseStatusUpdateService
    ): void {
        // Execute the AccountPauseByResourceIdService and return the resource
        $accountPause = $accountPauseByResourceIdService->execute(
            accountPauseResource: $this->resourceID
        );

        // Execute the AccountPauseStatusUpdateService
        $accountPauseStatusUpdateService->execute(
            accountPause: $accountPause,
            status: Statuses::COMPLETED->value
        );

        // Dispatch the AccountPauseResumePostActionsJob
        AccountPauseResumePostActionsJob::dispatch(
            accountPauseResource: $accountPause->resource_id,
        );

        // Dispatch the AccountPauseResumeNotificationJob
        AccountPauseResumeNotificationJob::dispatch(
            customerResource: $accountPause->pauseable->individual->customer->resource_id,
            accountPauseResource: $accountPause->resource_id,
        );
    }
}
