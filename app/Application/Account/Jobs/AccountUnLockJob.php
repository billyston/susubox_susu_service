<?php

declare(strict_types=1);

namespace App\Application\Account\Jobs;

use App\Domain\Account\Services\AccountLockByResourceIdService;
use App\Domain\Account\Services\AccountLockStatusUpdateService;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class AccountUnLockJob implements ShouldQueue
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
     * @param AccountLockByResourceIdService $accountLockByResourceIdService
     * @param AccountLockStatusUpdateService $accountLockStatusUpdateService
     * @return void
     * @throws SystemFailureException
     */
    public function handle(
        AccountLockByResourceIdService $accountLockByResourceIdService,
        AccountLockStatusUpdateService $accountLockStatusUpdateService
    ): void {
        // Execute the AccountLockByResourceIdService and return the resource
        $accountLock = $accountLockByResourceIdService->execute(
            accountLockResource: $this->resourceID
        );

        // Execute the AccountLockStatusUpdateService
        $accountLockStatusUpdateService->execute(
            accountLock: $accountLock,
            status: Statuses::COMPLETED->value
        );

        // Dispatch the AccountLockPostActionsJob
        AccountLockPostActionsJob::dispatch(
            accountLockResource: $accountLock->resource_id,
        );

        // Dispatch the AccountLockNotificationJob
        AccountLockNotificationJob::dispatch(
            customerResource: $accountLock->lockable->individual->customer->resource_id,
            accountLockResource: $accountLock->resource_id,
        );
    }
}
