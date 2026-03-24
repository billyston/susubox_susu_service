<?php

declare(strict_types=1);

namespace App\Application\Account\Schedulers\AccountPayout;

use App\Application\Account\Jobs\AccountPayoutLock\AccountPayoutUnLockJob;
use App\Domain\Account\Services\AccountPayoutLock\AccountPayoutUnlockDueDateService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class AccountPayoutUnLockScheduler implements ShouldQueue
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
     * @param AccountPayoutUnlockDueDateService $accountPayoutUnlockDueDateService
     * @return void
     * @throws SystemFailureException
     */
    public function handle(
        AccountPayoutUnlockDueDateService $accountPayoutUnlockDueDateService,
    ): void {
        // Execute the AccountPayoutUnlockDueDateService and return the Collection
        $accountPayoutUnlockDueDateService->execute(
            callback: function ($accountPayoutLocks) {
                // Loop the $accountLocks and dispatch the AccountPayoutUnLockJob
                foreach ($accountPayoutLocks as $accountPayoutLock) {
                    AccountPayoutUnLockJob::dispatch(
                        resourceID: $accountPayoutLock->resource_id
                    );
                }
            }
        );
    }
}
