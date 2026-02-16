<?php

declare(strict_types=1);

namespace App\Application\Account\Schedulers\AccountLock;

use App\Application\Account\Jobs\AccountUnLockJob;
use App\Domain\Account\Services\AccountLock\AccountUnlockDueDateService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class AccountUnLockScheduler implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
    ) {
        // ...
    }

    /**
     * @param AccountUnlockDueDateService $accountUnlockDueDateService
     * @return void
     * @throws SystemFailureException
     */
    public function handle(
        AccountUnlockDueDateService $accountUnlockDueDateService,
    ): void {
        // Execute the AccountUnlockDueDateService and return the Collection
        $accountUnlockDueDateService->execute(
            callback: function ($accountLocks) {
                // Loop the $accountLocks and dispatch the AccountUnLockJob
                foreach ($accountLocks as $accountLock) {
                    AccountUnLockJob::dispatch(
                        resourceID: $accountLock->resource_id
                    );
                }
            }
        );
    }
}
