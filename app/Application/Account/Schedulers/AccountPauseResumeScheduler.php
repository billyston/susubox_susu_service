<?php

declare(strict_types=1);

namespace App\Application\Account\Schedulers;

use App\Application\Account\Jobs\AccountPauseResumeJob;
use App\Domain\Account\Services\AccountPause\AccountPauseDueDateService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class AccountPauseResumeScheduler implements ShouldQueue
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
     * @param AccountPauseDueDateService $accountPauseDueDateService
     * @return void
     * @throws SystemFailureException
     */
    public function handle(
        AccountPauseDueDateService $accountPauseDueDateService,
    ): void {
        // Execute the AccountPauseDueDateService and return the Collection
        $accountPauseDueDateService->execute(
            callback: function ($accountPauses) {
                // Loop the $accountPauses and dispatch the AccountPauseResumeJob
                foreach ($accountPauses as $accountPause) {
                    AccountPauseResumeJob::dispatch(
                        resourceID: $accountPause->resource_id
                    );
                }
            }
        );
    }
}
